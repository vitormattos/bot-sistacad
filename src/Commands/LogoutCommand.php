<?php

namespace Commands;

use Telegram\Bot\Commands\Command;
use Base\DB;
use Base\Meetup;
use Base\UserMeta;
/**
 * Class LogoutCommand.
 */
class LogoutCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'logout';

    /**
     * @var string Command Description
     */
    protected $description = 'Desloga do Sistacad';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $message = $this->update->getMessage();
        $telegram_id = $message->getFrom()->getId();
        $userMeta = new UserMeta();
        $userMeta->deleteUser($telegram_id);
        $this->replyWithMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' =>
                "Você deslogou com suceso.\n".
                "Faça /start para se autenticar-se novamente",
        ]);
    }
}