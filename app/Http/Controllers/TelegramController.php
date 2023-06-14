<?php

namespace App\Http\Controllers;

use App\Models\TelegramUser;
use App\Telegram\QuizCallback;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Request as TelegramBotRequest;
use Longman\TelegramBot\Telegram;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use App\Telegram\TelegramQuizBot;

class TelegramController extends Controller
{
    private $telegram;
    private $telegramRequest;
    private $message;
    private $chatId;
    private $requestData;


    public function __construct()
    {
        $this->telegram = new Telegram("5077951331:AAEN44miGjYYmKlp5RUv25yulD_7kHgSarM", "everlo_bot");
        $this->telegramRequest = new TelegramBotRequest();
        $this->telegramRequest::initialize($this->telegram);

        $this->message = $this->getMessage();
        $this->log($this->requestData);
        $this->setChatId($this->message);
    }

    public function index()
    {
        if (!$this->isNewUser($this->chatId)) {
            $user = [
                'name' => $this->getUserName() ?: 'test',
                'chat_id' => $this->chatId,
            ];
            $this->log($user);
            TelegramUser::create($user);
        }

        $this->handle();
    }

    public function handle()
    {
        $type = $this->getType();
        $this->log($type);
        switch ($type) {
            case 'command':
                $text = $this->message['text'];
                $command = $this->getCommand($text);

                if ($command) {
                    $result = $command->action();
                    $this->sendMessage($this->chatId, $result);
                    break;
                }
            case 'callback':
                $callback = new QuizCallback($this->requestData['callback_query']);
                $response = $callback->getResponse();
                $this->sendMessage($this->chatId, $response);
                break;
        }
    }

    private function handleCallback($data)
    {
        if (!array_key_exists('data', $data)) {
            return;
        }

        $value = $data['data'];


        
    }

    private function handleQuiz($id)
    {
        $tb = new TelegramQuizBot($this->telegramRequest);
        $response = $tb->startQuiz($id);
        $this->sendMessage($this->chatId, $response);
    }

    private function getMessage()
    {
        $data = json_decode($this->telegramRequest->getInput(), true);
        $this->requestData = $data;

        $message = isset($data['message']) ? $data['message'] : (isset($data['callback_query']) ? $data['callback_query'] : null);

        if (is_null($message)) {
            throw new Exception('Message is not valid!!!');
        }

        return $message;
    }

    public function getUserName(): ?string
    {
        $name = $this->message['from']['username'] ?: null;

        return $name;
    }

    private function sendMessage(int $chatId, array $data): void
    {
        $data['chat_id'] = $this->chatId;
        $result = $this->telegramRequest::sendMessage($data);

        //$this->log($data);
    }

    protected function isCommand()
    {
        if ($type = isset($this->requestData['message']['entities'][0]['type'])) {
            
            return $this->requestData['message']['entities'][0]['type'] === 'bot_command';
        }

        return false;
    }

    protected function isCallback()
    {
        if ($type = isset($this->requestData['callback_query'])) {
            return true;
        }

        return false;
    }

    protected function getType(): string
    {
        if ($this->isCommand()) {
            return 'command';
        }

        if ($this->isCallback()) {
            return 'callback';
        }

        return 'default';
    }

    protected function getCommand($name)
    {
        $commands = $this->getCommands();

        foreach ($commands as $command) {
            if ($command->name === $name) {
                return $command;
            }
        }

        return null;
    }

    protected function getCommands()
    {
        $path = app_path() . '/Telegram/Commands';

        $directoryIterator = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path)
            ),
            '/^.+Command.php$/'
        );

        $commands = [];
        foreach ($directoryIterator as $dir) {
            $name = substr($dir->getFilename(), 0, -4);

            if (substr($name, -7) !== 'Command') {
                continue;
            }

            require_once $dir->getPathname();

            $command_obj = new $name();

            $commands[$name] = $command_obj;
        }

        return $commands;
    }

    public function isNewUser($id)
    {
        return DB::table('telegram_users')
            ->select('name')
            ->where('chat_id', $id)
            ->first();
    }

    public function setChatId($message): void
    {
        $id = isset($message['chat']['id']) ? $message['chat']['id'] : (isset($message['message']['chat']['id']) ? $message['message']['chat']['id'] : null);
        $this->chatId = $id;
    }

    protected function log(mixed $info)
    {
        Log::channel('telegram')->info($info);
    }
}
