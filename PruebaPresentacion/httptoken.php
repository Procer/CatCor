<?php
    /* TODO:Token de acceso a tu BOT */ 
    $botToken ="6051932745:AAFjR-D2ew1O9bLwdtKf-hS7vaMdo74Zt3E";

    /* TODO:URL del Webhook */
    $webhookurl="https://zaratesystemgroup.com.ar/CatCor/PruebaPresentacion/index.php";

    /* TODO:configura el webhook mediante una solicitud http */
    $apiurl = "https://api.telegram.org/bot$botToken/setWebhook?url=$webhookurl";
    $response = file_get_contents($apiurl);

    /* TODO:Verifica si la configuracion del webhook a sido exitosa */
    if($response === false){
        /* TODO:Captura el error si la solicitud HTTP falla */
        $error = error_get_last();
        echo "Error al configurar el webhook: ".$error['message'];
    }else{
        /* TODO:Verifica la respuesta de Telegram */
        $responsedata = json_decode($response,true);
        if($responsedata['ok']===true){
            echo "Wehbook Configurado con exito";
        }else{
            echo "Error al configurar Wehbook";
        }
    }
?>