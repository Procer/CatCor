<?php
require_once("include/vendor/autoload.php");

require_once("config/conexion.php");
require_once("models/Persona.php");

require("SubMenu.php");

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
        $message.="<b>B</b> - Contacto\n";
        $message.="C - Videos\n";
        $message.="D - TIPS para el auto\n";
        $message.="E - Errores en la pantalla\n";
        $message.="F - Iniciar sesión\n";

        $telegram->sendMessage($chatId,HTML,$message);

    }elseif($text ==='A' or $text ==='a' or $text ==='Nosotros' or $text ==='nosotros'){
        $thumbpath = 'img/NosotrosEjemplo.jpg';
        $telegram->sendPhoto($chatId, new CURLFile($thumbpath),"Concesionaria A. O. Sanchez  ",null,$keyboard);


        $informacion="Desde el año 2003 se destaca por ser la concesionaria número uno en ventas y servicios de Tesla a nivel país.
        \nNuestra calidad de servicio y atención al cliente está certificada por la norma ISO 9001:2008 Ref. Tüv Süd.";
        $telegram->sendMessage($chatId,$informacion);

        $telegram->sendMessage($chatId,$SubMenu);

    }elseif($text ==='C' or $text ==='c'){

        $keyboard = new InlineKeyboardMarkup(
            [
                [
                    [
                        'text' => 'VER ',
                        'url' => 'https://youtu.be/peqZjKyVSeI?si=pnowa8GuVzSYnFow&t=133'
                    ],
                ]
            ]
        );

        $thumbpath = 'img/NissanKicks.png';
        $telegram->sendPhoto($chatId, new CURLFile($thumbpath),"Nissan Kicks - Video explicativo",null,$keyboard);
  
        $keyboard = new InlineKeyboardMarkup(
            [
                [
                    [
                        'text' => 'VER ',
                        'url' => 'https://youtu.be/akkYTGWO6ps?si=tI7qEIB5K5niWLEV&t=133'
                    ],
                ]
            ]
        );

        $thumbpath = 'img/FordTerritory.png';
        $telegram->sendPhoto($chatId, new CURLFile($thumbpath),"Ford Territory - Video explicativo",null,$keyboard);       
        $telegram->sendMessage($chatId,$SubMenu);        
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

    }elseif($text === 'D' or $text === 'd'){

        $keyboard = new InlineKeyboardMarkup(
            [
                [
                    [
                        'text' => 'VER ',
                        'url' => 'https://www.youtube.com/watch?v=a98y9z1VrFM'
                    ],
                ]
            ]
        );

        $thumbpath = 'img/AndroidCar.png';
        $telegram->sendPhoto($chatId, new CURLFile($thumbpath),"TRUCOS ANDROID AUTO",null,$keyboard);
  
        $keyboard = new InlineKeyboardMarkup(
            [
                [
                    [
                        'text' => 'VER ',
                        'url' => 'https://www.youtube.com/watch?v=ynycVOn15Xw'
                    ],
                ]
            ]
        );

        $thumbpath = 'img/AppleCarPlay.png';
        $telegram->sendPhoto($chatId, new CURLFile($thumbpath),"TRUCOS APPLE CAR PLAY",null,$keyboard);

        $informacion="📌  Usá de forma correcta del freno de mano: Solo usalo para dejarlo estacionado en una pendiente.\n\n";
        $informacion.="📌  Mantené limpio el filtro del aire acondicionado: Para evitar que acumule polvo y suciedad.\n\n";
        $informacion.="📌  Controlá la presión de los neumáticos: Va a permitir que duren más, brindándote mayor seguridad.\n\n";
        $informacion.="📌  Evitá apoyar la mano en la palanca de cambio mientras manejás: Crea presión que puede desgastar los componentes internos.\n\n";
        $informacion.="📌  Siempre mantené un cuarto de tanque de combustible lleno: No olvides considerar las distancias programadas.\n\n";
        $informacion.="📌  Evitá manejar con el pie en el embrague: Si patina continuamente, puede quemarse.\n\n";
        $informacion.="📌  Cuidados al lavar el exterior del auto: consejos para proteger la pintura y la carrocería: Lavalo al menos 2 veces al mes y hacelo con paños de microfibra.\n\n";
        $informacion.="📌  Controlá el nivel de aceite: Con el objetivo de prevenir fallas y daños graves.\n\n";
        $informacion.="📌  Revisá el líquido refrigerante: Para que la temperatura del motor se mantenga estable.\n\n";
        $informacion.="📌  Chequeá los amortiguadores cada 30 mil kilómetros: Esto asegura la estabilidad y confort..\n\n";
        $telegram->sendMessage($chatId,$informacion);

    }elseif($text === 'B' or $text === 'b'){

        /* TODO: Define las coordenadas de latitud y longitud */
        $latitude = -59.034106;
        $longitude = -34.099206;
        /* TODO: Envia ubicación */
        $telegram->sendLocation($chatId,$longitude,$latitude);
        $informacion="📍 <b>Dirección:</b> Hipólito Yrigoyen 1757, B2800 Zárate, Provincia de Buenos Aires, Argentina.\n\n";
        $informacion.="📱 Telefono: 03487 666666 / 03487 555555 / 03487 444444\n\n";
        $informacion.="🕐 Horario de atencion: 08:00 hs - 20:00 hs\n\n";        
        $informacion.="🔗 Sitio Web\nhttps://zaratesystemgroup.com.ar/\n\n";
        $informacion.="🔗 INSTAGRAM\nhttps://www.instagram.com/zaratesystemgroup/\n\n";
        $informacion.="🔗 FACEBOOK\nhttps://www.facebook.com/zarasystemgroup/\n\n";           
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