<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function getQuizWithVariants($id)
    {
        $quiz = Quiz::find($id);
        $data = $quiz->toArray();

        $data = DB::table('quizzez')
            ->select('quizzez.*')    
            ->where('quizzez.id', '=', $id)
            

            ->get();

        return $data;
    }
}
