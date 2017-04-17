<?php

namespace Commands;

use Telegram\Bot\Commands\Command;
use Base\DB;
use Base\Meetup;
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
        $db = DB::getInstance();
        $res = $db->query('SELECT * FROM userdata WHERE telegram_id = :telegram_id', [
            'telegram_id' => int($telegram_id)
        ])->fetch();
        if(!$res) {
            //$arguments;
        }
        $this->replyWithMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' =>
                "Você deslogou com suceso.\n".
                "Faça /start para se autenticar-se novamente",
        ]);
    }
}