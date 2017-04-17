<?php

namespace Commands;

use Telegram\Bot\Commands\Command;
use Base\UserMeta;

/**
 * Class HelpCommand.
 */
class HelpCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'help';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'Help command, Get a list of commands';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $UserMeta = new UserMeta();
        $message = $this->update->getMessage();
        $user = $UserMeta->getUser($message->getFrom()->getId());
        if($user) {
            $this->telegram->removeCommand('logout');
        } else {
            $this->telegram->removeCommand('start');
        }

        $commands = $this->telegram->getCommands();
        $text = '';
        foreach ($commands as $name => $handler) {
            $text .= sprintf('/%s - %s'.PHP_EOL, $name, $handler->getDescription());
        }

        $this->replyWithMessage(compact('text'));
    }
}
