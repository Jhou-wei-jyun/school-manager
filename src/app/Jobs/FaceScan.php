<?php

namespace App\Jobs;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FaceScan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userAvatars;
    // protected $base_uri = '0.0.0.0:5001';
    // protected $base_uri = '10.112.10.69:5001';
    protected $base_uri = '118.163.212.125:5001';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $userAvatars = User::with('profile')->get()->pluck('profile.avatar');
        $this->userAvatars = $userAvatars->map(function ($userAvatar) {
            if ($userAvatar == null) {
                return null;
            }
            $userAvatar = 'avatar/small/' . $userAvatar;
            return $userAvatar;
        })->filter(function ($userAvatar) {
            return $userAvatar !== null;
        })->values();
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
            $response = $client->post('/scan', [
                'json' => [
                    "url" => $this->userAvatars,
                ]
            ]);
            $code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK
            $body = json_decode($response->getBody());
        }
    }
}
