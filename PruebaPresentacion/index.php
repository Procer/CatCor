<?php
require_once("include/vendor/autoload.php");

require_once("config/conexion.php");
require_once("models/Persona.php");

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

/* TODO:Configurar el token de acceso al tu BOT */
$telegram = new BotApi('6051932745:AAFjR-D2ew1O9bLwdtKf-hS7vaMdo74Zt3E');

/* TODO: Obtiene la actualizacion del webwook */
$update = json_decode(file_get_contents('php://input'));

/* TODO:Verificar si se recibio un mensaje de texto */
if(isset($update->message->text)){
    $chatId = $update->message->chat->id;
    $text = $update->message->text;

    /* TODO: Comprobar si el mensaje es "/start" */
    if($text==='Hola' or $text==='hola' or $text==='HOLA' or $text==='/start'){

		$mensajes = array(
			'Hola! Soy CatCor, el ChatBot 🤖 de prueba para concesionarias.',
			'😀 Holaa!! Bienvenido, soy CatCor. Probame.',
			'👋 Bienvenido/a. Soy un chatbot 🤖 de prueba para concesionarias.'
		);
		 
		$todo=(count($mensajes)-1);
		$num=rand(0,$todo);
        $message=$mensajes[$num];
        $message.="\n\nA - Nosotros\n";
        $message.="B - Contacto\n";
        $message.="C - Videos\n";
        $message.="D - TIPS para el auto\n";
        $message.="E - Errores en la pantalla\n";
        $message.="F - Iniciar sesión\n";

        $telegram->sendMessage($chatId,$message);

    }elseif($text ==='A' or $text ==='a' or $text ==='Nosotros' or $text ==='nosotros'){
        $thumbpath = 'img/NosotrosEjemplo.jpg';
        $telegram->sendPhoto($chatId, new CURLFile($thumbpath),"Concesionaria A. O. Sanchez  ",null,$keyboard);


        $informacion="Desde el año 2003 se destaca por ser la concesionaria número uno en ventas y servicios de Tesla a nivel país.
        \nNuestra calidad de servicio y atención al cliente está certificada por la norma ISO 9001:2008 Ref. Tüv Süd.";
        $telegram->sendMessage($chatId,$informacion);

        /*$menuMessage = "Aquí está el menú de opciones, elegir la opcion que necesite:\n\n";
        $menuMessage .= "1️⃣. Información del Curso. ❔\n";
        $menuMessage .= "2️⃣. Ubicacíon del local. 📍\n";
        $menuMessage .= "3️⃣. Enviar temario en pdf. 📄\n";
        $menuMessage .= "4️⃣. Audio explicando curso. 🎧\n";
        $menuMessage .= "5️⃣. Video de Introducción. ⏯️\n";
        $menuMessage .= "6️⃣. Hablar con Andercode. 🙋‍♂️\n";
        $menuMessage .= "7️⃣. Horario de Atención. 🕜\n";*/

        $telegram->sendMessage($chatId,$menuMessage);

    }elseif($text ==='/botones'){

        $keyboard = new InlineKeyboardMarkup(
            [
                [
                    [
                        'text' => 'Ir',
                        'url' => 'https://youtu.be/OL63dvaqyTY'
                    ],
                    [
                        'text' => 'Web',
                        'url' => 'https://anderson-bastidas.com'
                    ]
                ]
            ]
        );

        $thumbpath = 'assets/img.png';
        $telegram->sendPhoto($chatId, new CURLFile($thumbpath),"Unete al Canal de Youtube Andercode",null,$keyboard);
    }elseif(preg_match('/^\/dnitest (\d+)$/',$text,$matches)){

        $numeroDNI = $matches[1];

        $response="Consultando Información del DNI: ".$numeroDNI;
        $telegram->sendMessage($chatId,$response);
    }elseif(preg_match('/^\/dni (\d+)$/',$text,$matches)){

        $dni = $matches[1];

        $persona = new Persona();
        $datos=$persona->get_persona($dni);
        if(is_array($datos)==true and count($datos)>0){
            foreach($datos as $row){
                $respuesta = "Aquí el resultado:\n";
                $respuesta .= "Nombre: ".$row["per_nom"]."\n";
                $respuesta .= "Ape.Paterno: ". $row["per_apep"]."\n";
                $respuesta .= "Ape.Materno: ". $row["per_apem"]."\n";
                $respuesta .= "Dni: ". $row["per_dni"]."\n";
            }
        }else{
            $respuesta = "No se encontro información.";
        }

        $telegram->sendMessage($chatId,$respuesta);

    }elseif($text === '1'){

        $informacion="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
        $telegram->sendMessage($chatId,$informacion);

    }elseif($text === 'B' or $text === 'b'){

        /* TODO: Define las coordenadas de latitud y longitud */
        $latitude = -59.034106;
        $longitude = -34.099206;
        /* TODO: Envia ubicación */
        $telegram->sendLocation($chatId,new CURLFile($thumbpath),"Concesionaria A. O. Sanchez  ",null, $longitude,$latitude);
        $informacion="Dirección: Hipólito Yrigoyen 1757, B2800 Zárate, Provincia de Buenos Aires, Argentina.\n";
        $informacion.="Telefono: 03487 666666 / 03487 555555 / 03487 444444\n";
        $informacion.="Sitio Web: https://zaratesystemgroup.com.ar/\n";
        $informacion.="INSTAGRAM: https://www.instagram.com/zaratesystemgroup/\n";
        $informacion.="FACEBOOK: https://www.facebook.com/zarasystemgroup/\n";        
        $informacion.="YOUTUBE: https://www.youtube.com/channel/UC9pQkPVJD1f25xjWdGRBClA\n";            
        $telegram->sendMessage($chatId,$informacion);

    }elseif($text === '3'){

        $pdfpath = 'assets/test.pdf';
        $telegram->sendDocument($chatId, new CURLFile(realpath($pdfpath)));

        $message = "Aquí tienes el archivo pdf que solicitaste.";
        $telegram->sendMessage($chatId,$message);

    }elseif($text === '4'){

        $audiopath = 'assets/sample1.mp3';
        $telegram->sendAudio($chatId, new CURLFile(realpath($audiopath)));

        $message = "Aquí tienes el archivo de Audio que solicitaste.";
        $telegram->sendMessage($chatId,$message);
    }elseif($text === '5'){

        $message = "Aquí tienes el video de introducción al curso.";
        $telegram->sendMessage($chatId,$message);

        $linkyoutube ='https://youtu.be/OL63dvaqyTY';
        $telegram->sendMessage($chatId,$linkyoutube);

    }elseif($text === '6'){

        $message = "🤝 En breve me pondré en contacto contigo. 🤓";
        $telegram->sendMessage($chatId,$message);

    }elseif($text === '7'){

        $message = "📅 Horario de Atención: Lunes a Viernes. \n🕜 Horario: 9:00 a.m. a 5:00 p.m. 🤓";
        $telegram->sendMessage($chatId,$message);

    }elseif($text === 'Hola'){

        $telegram->sendMessage($chatId,"Hola como estas?");

    }else{

        $defaultMesage="No entiendo ese comando.Puedes usar /start para iniciar o /menu para ver el menu";
        $telegram->sendMessage($chatId,$defaultMesage);

    }

    /* $telegram->sendMessage($chatId,"Lo que escribio el usuario es: ".$chatId." | ".$text); */
}

?>