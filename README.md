# sistacad
Monitoramento de notas do sistema acadêmico do CEDERJ com notificação por Telegram

# Instalação
```bash
git clone https://github.com/vitormattos/sistacad
cd sistacad
composer install
```

Crie um bot no Telegram usando o [@BotFather](https://t.me/botfather) com o nome que desejar, precisamos apenas do token.

Dê start no seu bot e envie uma mensagem qualquer para ele.

Monte a url que segue com o token do bot que foi criado e acesse ela no navegador:

`https://api.telegram.org/bot<token>/getUpdates`

Isto irá retornar um json similar ao que segue:
```json
{"ok":true,"result":[{"update_id":85736329,
"message":{"message_id":3,"from":{"id":984536729,"first_name":"José","last_name":"das Couves","username":"JoseDasCouves"},"chat":{"id":984536729,"first_name":"José","last_name":"dasCouves","username":"JoseDasCouves","type":"private"},"date":1492302930,"text":"asfasdf"}}]}
```

Deste json você irá copiar o id, neste exemplo é *984536729*

Crie um arquivo na raiz do projeto chamado `.env` conforme o arquivo que segue abaixo substituindo as tags pelos dados obtidos anteriormente:
```bash
TELEGRAM_BOT_TOKEN=<token> # O token do seu bot
CHAT_ID=<id> # seu id do telegram
MATRICULA=<matricula> #sua matrícula
SENHA=<senha> # sua senha do sistema acadêmico
INTERVALO=300 # em segundos
NEW_STYLE="background-color:#99EE99;" # cor de fundo para as notas novas
```

Execute o arquivo `index.php` e deixe isto eternamente em execução. Você receberá uma mensagem com suas notas atuais. No dia que tiver atualização nas notas, você receberá uma mensagem no telegram vinda do bot que você criou com um PDF contendo suas notas.
```bash
php index.php
```
