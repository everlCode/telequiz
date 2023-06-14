<?php

namespace App\Telegram;

use Exception;
use Illuminate\Support\Facades\Log;

class QuizCallback
{
    public $type;
    public $data;

    function __construct(private array $callback)
    {

        if (!array_key_exists('data', $callback)) {
            throw new Exception('Callback doesnt contains data parameter');
        }

        $this->data = $this->getData();
        if (!isset($this->data['type'])) {
            throw new Exception('Callback doesnt contains type parameter');
        }

        $this->type = $this->data['type'];
    }

    public function getResponse(): array
    {
        switch ($this->type) {
            case 'answer':
                // $tq = new TelegramQuizBot();
                // $tq->startQuiz();
                break;
            case 'quiz':
                $id = $this->data['id'];
                $tq = new TelegramQuizBot();
                return $tq->startQuiz($id);
        }
    }

    public function getData(): array
    {
        $callbackData = json_decode($this->callback['data'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Callback data is not valid!!!');
        }

        return $callbackData;
    }
}
