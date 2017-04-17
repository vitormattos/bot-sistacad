<?php

namespace Commands;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Helpers\Emojify;
use Base\DB;
use Base\Meetup;
use Base\UserMeta;
/**
 * Class StartCommand.
 */
class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'start';

    /**
     * @var string Command Description
     */
    protected $description = 'Inicia o uso do bot';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $message = $this->update->getMessage();
        $telegram_id = $message->getFrom()->getId();
        $UserMeta = new UserMeta();
        if($user = $UserMeta->getUser($telegram_id)) {
            $this->replyWithMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => 'Bem vindo!',
            ]);
        } else {
            $this->replyWithMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => 'Você não está autenticado',
            ]);
        }
    }
}