<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function getQuizWithVariants($id)
    {
        $quiz = Quiz::find($id);
        $quiz = $quiz->toArray();
        
        $questions = Question::where('quiz_id', $id)->get()->toArray();
        //dd($questions);
        $questionsIds = [];
        foreach($questions as &$q) {
            $variants = Variant::where('question_id', $q['id'])->get()->toArray();
            $q['variants'] = $variants;
        }

        $quiz['questions'] = $questions;

        //dd($quiz);
        return $quiz;
    }
}
