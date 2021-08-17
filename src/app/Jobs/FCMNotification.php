<?php

namespace App\Jobs;

use FCM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use Illuminate\Support\Facades\File;

class FCMNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public $tries = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $title = $this->data['title'];
        $message = $this->data['message'];
        $tokens = $this->data['token'];
        // $system = $this->data['system'];
        $id = $this->data['id'];
        $type = $this->data['type'];
        $sound = $this->data['sound'];


        // $student_id = $this->data['student_id'];

        if (is_object($tokens)) {
            $tokens = $tokens->toArray();
        }

        if ($tokens == null) {
            return;
        }

        // $body = [
        //     'message' => "I'm message",
        //     'title' => 'you are title',
        //     'sound' => 'default',
        //     'body' => 'long_body',
        //     'img' => 'img',
        //     'url' => 'url',

        // ];

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        // $dataBuilder = new PayloadDataBuilder();
        // $dataBuilder->addData($body);

        // $notificationBuilder = new PayloadNotificationBuilder($title);
        // $notificationBuilder->setBody($message)->setSound($sound);
        // if ($system == 'ios') {

        //     $notificationBuilder = new PayloadNotificationBuilder($title);
        //     $notificationBuilder->setBody($message)->setSound($sound);

        //     $dataBuilder = new PayloadDataBuilder();

        //     $dataBuilder->addData(['type' => $type]);
        // }

        // if ($system == 'android')  {
        //     $notificationBuilder = new PayloadNotificationBuilder($title);
        //     $notificationBuilder->setBody($message)->setSound($sound);

        //     $dataBuilder = new PayloadDataBuilder();

        //     $dataBuilder->addData(['type' => $type]);

        //     // $dataBuilder->addData([
        //     //     'message' => $message,
        //     //     'title' => $title,
        //     //     'sound' => $sound,
        //     //     'type' => $type,
        //     //     'body' => 'long_body',
        //     //     'img' => 'img',
        //     //     'url' => 'url',
        //     // ]);
        // }
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($message)->setSound($sound)
            ->setClickAction("FLUTTER_NOTIFICATION_CLICK");

        $dataBuilder = new PayloadDataBuilder();

        if (array_key_exists('student_id', $this->data)) {
            $dataBuilder->addData([
                'id' => $id,
                'type' => $type,
                'student_id' => $this->data['student_id'],
            ]);
        } else {
            $dataBuilder->addData([
                'id' => $id,
                'type' => $type,
            ]);
        }

        $option = $optionBuilder->build();
        $data = $dataBuilder->build();
        $notification = $notificationBuilder->build();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        $succeed = $downstreamResponse->numberSuccess();
        $fail = $downstreamResponse->numberFailure();
        $number = $downstreamResponse->numberModification();

        /** 加log觀察 */
        $log_file_path = storage_path('notify.log');
        $log_info = [
            'date' => date('Y-m-d H:i:s'),
            'tokens' => $tokens,
            'succeed' => $succeed,
            'fail' => $fail,
            'number' => $number
        ];

        $log_info_json = json_encode($log_info) . "\r\n";
        File::append($log_file_path, $log_info_json);
    }
}
