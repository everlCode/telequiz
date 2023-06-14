<?php

namespace App\Telegram;

use App\Models\Question;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Request;

class TelegramQuizBot
{
    public function startQuiz($id)
    {
        $question = Question::where('quiz_id', $id);
        $question = $question->first();

        // $variants = Variant::where('question_id', $question['id'])->get();
        $variants = DB::table('question', 'q')
            ->select('v.name')
            ->join('variants as v', 'v.question_id', 'q.id')
            ->where('quiz_id', '=', $id)
            ->where('q.id', '=', $question['id'])
            ->get();

        $buttons = $this->getVarinatsButtons($variants->toArray());
        $firstQuestion['text'] = $question['name'];
        $firstQuestion['reply_markup'] = $buttons;
        Log::debug($firstQuestion);
        return $firstQuestion;
    }

    protected function getVarinatsButtons($variants)
    {
        $output = [];
        foreach ($variants as $q) {

            $output[] = [
                'text' => $q->name,
                'callback_data' => '[{"type": "answer", "value": ' . $q->name . '""}]'
            ];
        }

        $buttons = [
            'inline_keyboard' => [$output]
        ];

        return $buttons;
    }
}
