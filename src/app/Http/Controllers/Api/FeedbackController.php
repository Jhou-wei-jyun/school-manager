<?php

namespace App\Http\Controllers\Api;

use App\API\ApiHelper;
use App\Feedback;
use App\FeedbackType;
use App\Parents;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    use ApiHelper;

    public function selectType(Request $request)
    {
        return $this->succeed(FeedbackType::all(), 200);
    }

    public function store(Request $request)
    {
        $teacher_id = $request->teacher_id;
        $parent_id = $request->parent_id;
        $feedback_type_id = (int)$request->feedback_type_id;
        $feedback = $request->feedback;
        $timezone = config('services.time_zone');
        $now = Carbon::now($timezone)->format('Y-m-d H:i:s');
        try {
            if ($teacher_id) {
                $teacher = User::find($teacher_id);
                $check = $this->timeCheck($teacher->feedbacks->last(), $now);
                if ($check) {
                    $feedback_add = new Feedback([
                        'feedback' => $feedback,
                        'feedback_type_id' => $feedback_type_id,
                    ]);
                    $teacher->feedbacks()->save($feedback_add);
                    return $this->succeed('新增成功', 200);
                } else {
                    return $this->error('5分鐘內無法回應超過1次', 505);
                }
            } else if ($parent_id) {
                $parent = Parents::find($parent_id);
                $check = $this->timeCheck($parent->feedbacks->last(), $now);
                if ($check) {
                    $feedback_add = new Feedback([
                        'feedback' => $feedback,
                        'feedback_type_id' => $feedback_type_id,
                    ]);
                    $parent->feedbacks()->save($feedback_add);
                    return $this->succeed('新增成功', 200);
                } else {
                    return $this->error('5分鐘內無法回應超過1次', 505);
                }
            }
            return $this->error('parent_id or teacher_id is not exist', 501);
        } catch (\Exception $e) {
            return $this->error('新增失敗', 506);
        }
    }
    public function timeCheck($last_feedback, $now)
    {
        $check = false;
        if ($last_feedback) {
            $diff_minute = carbon::parse($now)->diffInMinutes(Carbon::parse($last_feedback->created_at), true);
            if ($diff_minute < 5) {
                return $check;
            } else if ($diff_minute >= 5) {
                $check = true;
                return $check;
            }
        } else {
            $check = true;
            return $check;
        }
    }
}
