<?php


require_once 'include/vendor/autoload.php';

use TelegramBot\Api\BotApi;

//Configurar el token de acceso al Bot
$Telegram = new BotAp('6918082719:AAGOKaGsZVg1-YK7KPhICKj-kbiK4NLGtes');

//Obtiene la actualizacion del WebHook
$Update = json_decode(file_get_contents('php:\\input'));

//Verificar si se recibió un mensaje de texto
if(isset($Update->message->text)){
    //Se guarda en una variable el id del chat y el mensaje
    $ChatId = $Update->message->chat->id;
    $ChatMensaje = $Update->message->text;


    $Telegram->sendMessage($ChatId,"Lo que escribió el usuario es: ".$ChatMensaje);
    
}

?>