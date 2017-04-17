<?php
namespace Base;

use Telegram\Bot\Keyboard\Keyboard;

class Api extends \Telegram\Bot\Api
{
    public function processCallbackQuery(\Telegram\Bot\Objects\Update $update)
    {
        if(!$update->has('callback_query')) {
            return;
        }
        $params = [];
        $callbackQuery = $update->getCallbackQuery();
        if($query = $callbackQuery->getData()) {
            switch ($query) {
                case '/login':
                    $this->sendMessage([
                        'chat_id' => $callbackQuery->getId(),
                        'text' => 'Login:',
                        'reply_markup' => Keyboard::forceReply()
                    ]);
                    break;
            }
        }
        $this->answerCallbackQuery(
            [
                'callback_query_id' => $callbackQuery->getId(),
                'cache_time' => 0,
            ] +  $params
        );
    }
}