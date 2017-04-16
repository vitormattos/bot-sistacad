<?php
use Goutte\Client;
use Telegram\Bot\Api;

require 'vendor/autoload.php';

if(file_exists('.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}

$telegram = new Api();
$client = new Client();
$anterior = null;

$guzzleClient = new \GuzzleHttp\Client(['timeout' => 60]);

$client->setClient($guzzleClient);
$crawler = $client->request('POST', 'http://sistacad.cederj.edu.br/inicio.asp', [
    'txtLogin'    => getenv('MATRICULA'),
    'txtPassword' => getenv('SENHA')
]);

$count = 0;
while(true) {
    echo "\r".($count++);
    if(!isset($idSemestre)) {
        $semestres = $client->request('POST', 'http://sistacad.cederj.edu.br/notassemestre.asp?ajaxtipo=pega_semestreano');
        $idSemestre = $semestres->filter('option')->attr('value');
    }

    $notas = $client->request('POST', 'http://sistacad.cederj.edu.br/notassemestre.asp?ajaxtipo=pega_notas&osemestreanosel='.$idSemestre);
    $notasHTML = $notas->getNode(0)->C14N();
    $notasHTML = str_replace(['&#xD;', '<br></br>'], ['', '<br />'], $notasHTML);

    if($anterior && $anterior != $notasHTML) {
        $html2pdf = new HTML2PDF();
        $html2pdf->writeHTML($notasHTML);
        @$html2pdf->Output(getenv('MATRICULA').'.pdf', 'F');

        $telegram->sendDocument([
            'chat_id' => getenv('CHAT_ID'),
            'document' => getenv('MATRICULA').'.pdf',
            'caption' => 'Tem nota nova!'
        ]);   }
    $anterior = $notasHTML;
//     sleep(60*5);
}