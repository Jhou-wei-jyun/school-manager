<?php

namespace App\Jobs;

use App\Photo;
use App\Profile;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class PythonFaceRecognition implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $photo_id;
    protected $department_id;
    protected $file_path;
    protected $file_name;
    // protected $base_uri = '0.0.0.0:5001';
    // protected $base_uri = '10.112.10.69:5001';
    protected $base_uri = '118.163.212.125:5001';
    protected $storage_path;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($photo_id, $file_path, $file_name, $department_id)
    {
        $this->photo_id = $photo_id;
        $this->department_id = $department_id;
        $this->file_path = $file_path;
        $this->file_name = $file_name;
        $this->storage_path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->attempts() == 1) {
            $client = new Client(['base_uri' => "http://" . $this->base_uri]);
            $response = $client->post('/', [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($this->storage_path . $this->file_path, 'r'),
                        'filename' => $this->file_name
                    ],
                ]
            ]);
            $code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK
            $body = json_decode($response->getBody());
            // print('' . join($body));
            $user_id_arr = [];
            foreach ($body as $user) {
                $pieces = preg_split("/S|T|-/", $user);
                $user_id_arr[] = (int)$pieces[1];
            }
            $photo = Photo::find($this->photo_id);
            $user_id_arr_filter = User::whereIn('users.id', $user_id_arr)
                ->leftjoin('departments', 'users.id', '=', 'departments.supervisor_id')
                ->where(function ($query) {
                    $query
                        ->where('users.department_id', $this->department_id)
                        ->orWhere('departments.id', $this->department_id);
                })
                ->where('users.is_actived', true)
                ->select('users.id')
                ->get();


            $photo->userTags()->sync($user_id_arr_filter);
        }
    }
}
