<?php

namespace Commands;

use Telegram\Bot\Commands\Command;
use Base\DB;
use Base\Meetup;
/**
 * Class LogoutCommand.
 */
class AboutCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'about';

    /**
     * @var string Command Description
     */
    protected $description = 'Sobre este bot';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $message = $this->update->getMessage();
        $this->replyWithMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' =>
                "Apenas um cliente para o sistema acadêmico do CEDERJ\n\n".
                "Código fonte em: github.com/vitormattos/sistacad",
        ]);
    }
}