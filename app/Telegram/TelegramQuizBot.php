<?php

namespace App\Telegram;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Request;

class TelegramQuizBot
{
    private $userId;

    public function startQuiz($id)
    {
        DB::table('answers', 'a')
            ->join('question as qs', 'a.question_id', '=', 'qs.id')
            ->join('quizzez as q', 'qs.quiz_id', '=', 'q.id')
            ->where('q.id', '=', $id)
            ->delete();

        $question = Question::where('quiz_id', $id);
        $question = $question->first();

        $variants = DB::table('question', 'q')
            ->select(['v.name', 'v.id', 'q.quiz_id'])
            ->join('variants as v', 'v.question_id', 'q.id')
            ->where('quiz_id', '=', $id)
            ->where('q.id', '=', $question['id'])
            ->get();

        $buttons = $this->getVarinatsButtons($variants->toArray(), $question);
        $firstQuestion['text'] = "Question #" . $question['id'] . ' ' . $question['name'];
        $firstQuestion['reply_markup'] = $buttons;

        return $firstQuestion;
    }

    public function renderQuestion($questionId)
    {   
        $question = Question::find($questionId)->toArray();

        $data = DB::table('variants', 'v')
        ->join('question as q', 'v.question_id', '=', 'q.id')
        ->join('quizzez as qz', 'q.quiz_id', '=', 'qz.id')
        ->where('q.id', $questionId)
        ->get(['v.name', 'v.id']);
            
        $data = $data->toArray();

        $buttons = $this->getVarinatsButtons($data, $question);
        $firstQuestion['text'] = "Question #" . $question['id'] . ' ' . $question['name'];
        $firstQuestion['reply_markup'] = $buttons;
       
        return $firstQuestion;
    }

    public function nextQuestion(int $prevQuestion)
    {
        $question = Question::find($prevQuestion)->toArray();
        
        $nextQuestion = $this->getNextQuestion($question['quiz_id'], $prevQuestion);

        return $this->renderQuestion($nextQuestion->id);
    }

    protected function getVarinatsButtons($variants, $question)
    {
        $output = [];
        foreach ($variants as $v) {
            $output[] = [
                'text' => $v->name,
                'callback_data' => '{"type":"answer","qz":"' . $question['quiz_id'] . '","qn":"' . $question['id'] . '","var":"' . $v->id . '"}'
            ];
        }

        $buttons = [
            'inline_keyboard' => [$output]
        ];

        return $buttons;
    }

    public function isRightAnswer(int $questionId, int $variantId)
    {
        return DB::table('variants', 'v')
            ->where('question_id', '=', $questionId)
            ->where('v.id', '=', $variantId)
            ->where('v.is_right', '=', true)
            ->count();
    }

    public function getNextQuestion(int $quizId, int $prevQuestion)
    {
        $lq = DB::table('question', 'q')
            ->select('q.id')
            ->join('quizzez as qz', 'q.quiz_id', '=', 'qz.id')
            ->where('qz.id', '=', $quizId)
            ->where('q.id', '>', $prevQuestion)
            ->orderBy('q.id', 'asc')
            ->limit(1)
            ->get()
            ->toArray();
        
        return $lq[0];
    }

    public function renderAnswer($quizId, $questionId, $variantId)
    {
        $isRight = $this->isRightAnswer($questionId, $variantId);

        $questionsCount = DB::table('question')
            ->select('*')
            ->where('quiz_id', '=', $quizId)
            ->count();
        
        $questionAnswered = DB::table('answers', 'a')
            ->select('*')
            ->join('question as q', 'a.question_id', 'q.id')
            ->join('variants as v', function($join){
                $join->on('v.question_id', '=', 'q.id');
                $join->on('v.id', '=', 'a.variant_id');
            })
            ->where('user_id', '=', $this->userId)
            ->where('q.quiz_id', '=', $quizId)
            ->count();
            
        $questionAnsweredRight = DB::table('answers', 'a')
            ->select('*')
            ->join('question as q', 'a.question_id', 'q.id')
            ->join('variants as v', function($join){
                $join->on('v.question_id', '=', 'q.id');
                $join->on('v.id', '=', 'a.variant_id');
            })
            ->where('user_id', '=', $this->userId)
            ->where('q.quiz_id', '=', $quizId)
            ->where('is_right', '=', true)
            ->count();

            

        $response['text'] = $isRight ? '<b> Right! </b>' : '<b> Wrong ( </b>' . PHP_EOL;

        $response['text'] .= 'You answerd ' . $questionAnsweredRight . ' from ' . $questionsCount . PHP_EOL;
       Log::debug($questionsCount);
       Log::debug('count');

       Log::debug($questionAnswered);
       Log::debug('answered');

        if ($questionsCount === $questionAnswered) {
            $response['text'] .= ' You finished quiz!!! ' . PHP_EOL . ' <b>Your result: </b>' . $questionAnsweredRight . '/' . $questionsCount ;
            
        } else {
            $response['reply_markup'] = $this->getNextQuestionButton($questionId);
        }


        return $response;
    }

    public function setUserId(int $id)
    {
        $this->userId = $id;
    }

    public function getNextQuestionButton($questionId)
    {
        $output[] = [
            'text' => 'Next',
            'callback_data' => '{"type":"next_question", "q_id":"' . $questionId . '"}'
        ];

        return [
            'inline_keyboard' => [$output]
        ];
    }
}
