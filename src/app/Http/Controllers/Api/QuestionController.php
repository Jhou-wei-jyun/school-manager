<?php

namespace App\Http\Controllers\Api;

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
}
