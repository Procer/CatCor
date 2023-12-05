<?php

$BotToken = "6918082719:AAGOKaGsZVg1-YK7KPhICKj-kbiK4NLGtes";
$WebHookUrl = "https://zaratesystemgroup.com.ar/CatCor/PruebaPresentacion/inde.php";
$AppiUrl = "https://api.telegram.org/bot$BotToken/setwebhook?url=$WebHookUrl";
$Response = file_get_contents($AppiUrl);


if($Response == false){
    $Error = error_get_last($AppiUrl);
    echo "Error al configurar el webhook: ".$Error['message'];
} else {
    $ResponseData = json_decode($Response,true);
    if($ResponseData['ok'] == true){
        echo "WebHook configurado con exito";
    } else {
        echo "Error al configurar WebHook";
    }
}

//echo $Response;

?>