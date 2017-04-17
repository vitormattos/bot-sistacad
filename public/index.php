<?php
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

require_once '../vendor/autoload.php';

if(file_exists('../.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__.'/../');
    $dotenv->load();
}
if(getenv('MOCK_JSON')) {
    class mockApi extends Api{
        public function getWebhookUpdates($emitUpdateWasReceivedEvent = true) {
            return new Update(json_decode(getenv('MOCK_JSON'), true));
        }
    }
    $telegram = new mockApi();
} else {
    error_log(file_get_contents('php://input'));
    $telegram = new Api();
}

// Classic commands
$telegram->addCommands([
    \Commands\HelpCommand::class,
    \Commands\StartCommand::class,
    \Commands\LogoutCommand::class,
    \Commands\AboutCommand::class
]);

$update = $telegram->getWebhookUpdates();
foreach(['InlineQuery', 'Command'] as $method) {
    call_user_func([$telegram, 'process'.$method], $update);
    if($telegram->getLastResponse()) {
        break;
    }
}