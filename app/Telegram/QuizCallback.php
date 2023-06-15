<?php

namespace App\Telegram;

use App\Models\Answer;
use Exception;
use Illuminate\Support\Facades\Log;

class QuizCallback
{
    public $type;
    public $data;
    public $userId;

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
        $tq = new TelegramQuizBot();
        $tq->setUserId($this->userId);

        switch ($this->type) {
            case 'answer':
                $quizId = $this->data['qz'];
                $questionId = $this->data['qn'];
                $variantId = $this->data['var'];

                Answer::create([
                    'user_id' => $this->userId,
                    'question_id' => $questionId,
                    'variant_id' => $variantId
                ]);

                $answer = $tq->renderAnswer($quizId, $questionId, $variantId);

                return $answer;
                break;
            case 'question':
                $id = $this->data['id'];
                return $tq->startQuiz($id);
            case 'next_question':
                $id = $this->data['q_id'];
                return $tq->nextQuestion($id);
                break;
        }
    }

    public function setUserId(int $id)
    {
        $this->userId = $id;
    }

    public function getData(): mixed
    {   Log::debug($this->callback['data']);
        $callbackData = json_decode($this->callback['data'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Callback data is not valid!!!');
        }

        return $callbackData;
    }
}
