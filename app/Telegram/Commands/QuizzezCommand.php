<?php

use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizzezCommand
{

    public $name = '/quizzez';

    public function action()
    {
        $buttons = $this->getQuizzezButtons();

        $data['text'] = 'Choose quiz';
        $data['reply_markup'] = $buttons;
        Log::debug($data);
        return $data;
    }

    protected function getQuizzezButtons()
    {
        $quizzez = DB::table('quizzez')
            ->select(['id', 'name'])
            ->get()
            ->toArray();

        $output = [];    
        foreach($quizzez as $q) {
            $output[] = [
                'text' => $q->name,
                'callback_data' => '{"type":"question", "command": "start", "id":' . $q->id . '}' 
            ];
        }
        
        $buttons = [
            'inline_keyboard' => [$output]
        ];

        return $buttons;
    }
}
