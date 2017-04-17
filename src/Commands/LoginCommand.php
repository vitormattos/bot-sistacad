<?php

namespace Commands;

use Telegram\Bot\Commands\Command;
use Base\DB;
use Base\Meetup;
use Base\UserMeta;
use Telegram\Bot\Keyboard\Keyboard;
/**
 * Class LoginCommand.
 */
class LoginCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'login';

    /**
     * @var string Command Description
     */
    protected $description = 'Autenticar-se';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $message = $this->update->getMessage();
        $telegram_id = $message->getFrom()->getId();
        $userMeta = new UserMeta();
        $user = $userMeta->getUser($telegram_id);
        if(!$user) {
            $login = $message->getText();
            if(!is_numeric($login)) {
                $this->telegram->sendMessage([
                    'chat_id' => $telegram_id,
                    'text' => 'Login inválido.',
                ]);
                $this->telegram->sendMessage([
                    'chat_id' => $telegram_id,
                    'text' => 'Login:',
                    'reply_markup' => Keyboard::forceReply()
                ]);
            } else {
                $userMeta->newUser($telegram_id, (int)$login);
                $this->telegram->sendMessage([
                    'chat_id' => $telegram_id,
                    'text' => 'Senha:',
                    'reply_markup' => Keyboard::forceReply()
                ]);
            }
            //$arguments;
        } else {
            if(!$user['password']) {
                if($message->has('reply_to_message')) {
                    $text = $message->getReplyToMessage()->getText();
                    if($text == 'Senha:') {
                        $userMeta->updateUser($telegram_id, [
                            'username' => (int)$user['username'],
                            'password' => $user['password'] = $message->getText()
                        ]);
                    }
                }
                if(!$user['password']) {
                    $this->telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => 'Senha:',
                        'reply_markup' => Keyboard::forceReply()
                    ]);
                } else {
                    $valid = $userMeta->validateUser($telegram_id, $user['username'], $user['password']);
                    if($valid) {
                        if(!$user['name']) {
                            $user = $userMeta->getUser($telegram_id);
                        }
                        $this->telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => 'Bem vindo '.$user['name'] .'!',
                        ]);
                    } else {
                        $this->telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => 'Usuário ou senha inválidos. Faça /start para se autenticar',
                        ]);
                    }
                }
            } else {
                $valid = $userMeta->validateUser($telegram_id, $user['username'], $user['password']);
                if($valid) {
                    if(!$user['name']) {
                        $user = $userMeta->getUser($telegram_id);
                    }
                    $this->telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => 'Bem vindo '.$user['name'] .'!',
                    ]);
                } else {
                    $this->telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => 'Usuário ou senha inválidos. Faça /start para se autenticar',
                    ]);
                }
            }
        }
    }
}