<?php

namespace Commands;

use Telegram\Bot\Commands\Command;
use Base\UserMeta;
use Goutte\Client;
/**
 * Class LoginCommand.
 */
class NotasCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'notas';

    /**
     * @var string Command Description
     */
    protected $description = 'Notas do semestre';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $message = $this->update->getMessage();
        $telegram_id = $message->getFrom()->getId();

        $userMeta = new UserMeta();
        $user = $userMeta->getUser($telegram_id);
        if(!$user || !$user['password']) {
            $this->telegram->sendMessage([
                'chat_id' => $telegram_id,
                'text' => 'Usuário ou senha inválidos. Faça /start para se autenticar',
            ]);
            return;
        } else {
            $valid = $userMeta->validateUser($telegram_id, $user['username'], $user['password']);
            if(!$valid) {
                $this->telegram->sendMessage([
                    'chat_id' => $telegram_id,
                    'text' => 'Usuário ou senha inválidos. Faça /start para se autenticar',
                ]);
                return;
            }
        }

        $client = new Client();

        $guzzleClient = new \GuzzleHttp\Client(['timeout' => 60]);

        $client->setClient($guzzleClient);
        $client->request('POST', 'http://sistacad.cederj.edu.br/inicio.asp', [
            'txtLogin'    => $user['username'],
            'txtPassword' => $user['password']
        ]);

        $client->request('GET', 'http://sistacad.cederj.edu.br/notassemestre.asp?ajaxtipo=pega_semestreano');
        $html = $client->getResponse()->getContent();
        preg_match('/value="(?<idSemestre>[0-9]+)"/', $html, $idSemestre);
        $idSemestre = $idSemestre['idSemestre'];

        $client->request('GET', 'http://sistacad.cederj.edu.br/notassemestre.asp?ajaxtipo=pega_notas&osemestreanosel='.$idSemestre);
        $notasHTML = $client->getResponse()->getContent();
        $notasHTML.= '<style>'.file_get_contents('css/style.css').'</style>';
        $notasHTML = str_replace(["\r", '<br></br>'], ['', '<br />'], $notasHTML);
        $notasHTML = trim($notasHTML);

        file_put_contents(
            '../storage/'.getenv('MATRICULA').'.html',
            $notasHTML
        );

        $this->telegram->sendDocument([
            'chat_id'  => $telegram_id,
            'document' => $user['username'].'.html',
            'caption'  => 'Suas notas atuais'
        ]);
    }
}