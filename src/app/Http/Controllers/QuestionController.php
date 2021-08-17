<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Question;
use App\API\ApiHelper;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    use ApiHelper;
    public function index(Request $request)
    {
        $questions = Question::select('question_id', 'question', 'answer')->get();
        return $this->succeed($questions, 200);
    }
    public function store(Request $request)
    {
        $admin_id = $request->admin_id;
        $question = $request->question;
        $answer = $request->answer;
        $question_add = new Question([
            'question' => $question,
            'answer' => $answer,
        ]);
        $question_add->save();
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($question_add)
                ->withProperties([
                    'type' => 'store',
                    'result' => 'success',
                ])
                ->log('新增提問');
        }
        return $this->succeed('新增成功', 200);
    }
    public function update(Request $request)
    {
        $admin_id = $request->admin_id;
        $question_id = $request->question_id;
        $question = $request->question;
        $answer = $request->answer;
        $question_model = Question::find($question_id);
        $ori_question_model = tap($question_model)->update([
            'question' => $question,
            'answer' => $answer,
        ]);
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($ori_question_model)
                ->withProperties([
                    'type' => 'edit',
                    'result' => 'success',
                ])
                ->log('編輯提問');
        }
        return $this->succeed('更新成功', 200);
    }
    public function delete(Request $request)
    {
        $question_id = $request->question_id;
        $question = Question::find($question_id);
        $question->delete();
        return $this->succeed('刪除成功', 200);
    }
}
