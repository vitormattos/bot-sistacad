<?php
use Goutte\Client;
use Telegram\Bot\Api;
use Phalcon\Diff;
use Sistacad\Diff\Renderer\Diff_Render;

require 'vendor/autoload.php';

ini_set('display_errors', 0);
error_reporting(E_ERROR);

if(file_exists('.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}

$telegram = new Api();
$client = new Client();

$guzzleClient = new \GuzzleHttp\Client(['timeout' => 60]);

$client->setClient($guzzleClient);
$client->request('POST', 'http://sistacad.cederj.edu.br/inicio.asp', [
    'txtLogin'    => getenv('MATRICULA'),
    'txtPassword' => getenv('SENHA')
]);

$anterior = null;
$count = 0;
while(true) {
    if(!isset($idSemestre)) {
        $client->request('GET', 'http://sistacad.cederj.edu.br/notassemestre.asp?ajaxtipo=pega_semestreano');
        $html = $client->getResponse()->getContent();
        preg_match('/value="(?<idSemestre>[0-9]+)"/', $html, $idSemestre);
        $idSemestre = $idSemestre['idSemestre'];
    }

    $client->request('GET', 'http://sistacad.cederj.edu.br/notassemestre.asp?ajaxtipo=pega_notas&osemestreanosel='.$idSemestre);
    $notasHTML = $client->getResponse()->getContent();
    $notasHTML.= '<style>'.file_get_contents('public/css/style.css').'</style>';
    $notasHTML = str_replace(["\r", '<br></br>'], ['', '<br />'], $notasHTML);
    $notasHTML = trim($notasHTML);

    if(!$anterior || $anterior != $notasHTML) {
        $diff = new Diff(
            explode("\n", $anterior?:$notasHTML),
            explode("\n", $notasHTML),
            ['context' => 0]
        );
        file_put_contents(
            getenv('MATRICULA').'.html',
            $diff->render(new Diff_Render([
                'style' => getenv('NEW_STYLE')
            ]))
        );

        $telegram->sendDocument([
            'chat_id' => getenv('CHAT_ID'),
            'document' => getenv('MATRICULA').'.html',
            'caption' => !$anterior?'Suas notas atuais':'Tem nota nova!'
        ]);
    }
    $anterior = $notasHTML;
    echo "\rExecução ".(++$count);
    echo ' próxima execução dentro de '. getenv('INTERVALO') . ' segundos';
    sleep(getenv('INTERVALO'));
}