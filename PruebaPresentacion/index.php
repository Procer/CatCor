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
/* ############ HOLA ############*/
    if($text==='Hola' or $text==='hola' or $text==='HOLA' or $text==='/start'){

		$mensajes = array(
			'Hola! Soy CatCor, el ChatBot 🤖 de prueba para concesionarias.',
			'😀 Holaa!! Bienvenido, soy CatCor. Probame.',
			'👋 Bienvenido/a. Soy un chatbot 🤖 de prueba para concesionarias.'
		);
		 
        shuffle($mensajes);
        $i = 1;
        foreach ($mensajes as $mensaje) {
          if($i < 2)
          $message= $mensaje;
          $i++;
        }
        $message.="\n\n<b>A</b> - /Nosotros\n";
        $message.="<b>B</b> - /Contacto\n";
        $message.="<b>C</b> - /Videos\n";
        $message.="<b>D</b> - /TIPS\n";
        $message.="<b>E</b> - /ErroresPantalla\n";
        $message.="<b>F</b> - /IniciarSesion\n";

        $telegram->sendMessage($chatId,$message, 'HTML');

/* ############ NOSOTROS ############*/        
    }elseif($text ==='A' or $text ==='a' or $text ==='Nosotros' or $text ==='nosotros' or $text ==='/Nosotros'){
        $thumbpath = 'img/NosotrosEjemplo.jpg';
        $telegram->sendPhoto($chatId, new CURLFile($thumbpath),"Concesionaria A. O. Sanchez  ",null,$keyboard);


        $informacion="Desde el año 2003 se destaca por ser la concesionaria número uno en ventas y servicios de Tesla a nivel país.
        \nNuestra calidad de servicio y atención al cliente está certificada por la norma ISO 9001:2008 Ref. Tüv Süd.";
        $telegram->sendMessage($chatId,$informacion);

        $telegram->sendMessage($chatId,$SubMenu,'HTML');

    }elseif($text ==='C' or $text ==='c' or $text ==='/Videos'){

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
        $telegram->sendMessage($chatId,$SubMenu,'HTML');     

/* ############  ############*/        
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

/* ############ TIPS ############*/        
    }elseif($text === 'D' or $text === 'd' or $text === '/TIPS'){

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

        $informacion="📌  Usá de forma correcta del freno de mano: <i>Solo usalo para dejarlo estacionado en una pendiente.</i>\n\n";
        $informacion.="📌  Mantené limpio el filtro del aire acondicionado: <i>Para evitar que acumule polvo y suciedad.</i>\n\n";
        $informacion.="📌  Controlá la presión de los neumáticos: <i>Va a permitir que duren más, brindándote mayor seguridad.</i>\n\n";
        $informacion.="📌  Evitá apoyar la mano en la palanca de cambio mientras manejás: <i>Crea presión que puede desgastar los componentes internos.</i>\n\n";
        $informacion.="📌  Siempre mantené un cuarto de tanque de combustible lleno: <i>No olvides considerar las distancias programadas.</i>\n\n";
        $informacion.="📌  Evitá manejar con el pie en el embrague: <i>Si patina continuamente, puede quemarse.</i>\n\n";
        $informacion.="📌  Cuidados al lavar el exterior del auto: <i>consejos para proteger la pintura y la carrocería: Lavalo al menos 2 veces al mes y hacelo con paños de microfibra.</i>\n\n";
        $informacion.="📌  Controlá el nivel de aceite: <i>Con el objetivo de prevenir fallas y daños graves.</i>\n\n";
        $informacion.="📌  Revisá el líquido refrigerante: <i>Para que la temperatura del motor se mantenga estable.</i>\n\n";
        $informacion.="📌  Chequeá los amortiguadores cada 30 mil kilómetros: <i>Esto asegura la estabilidad y confort.</i>\n\n";
        $telegram->sendMessage($chatId,$informacion);

/* ############ CONTACTO ############*/        
    }elseif($text === 'B' or $text === 'b' or $text === '/Contacto'){

        /* TODO: Define las coordenadas de latitud y longitud */
        $latitude = -59.034106;
        $longitude = -34.099206;
        /* TODO: Envia ubicación */
        $telegram->sendLocation($chatId,$longitude,$latitude);
        $informacion="📍 <b>Dirección:</b> Hipólito Yrigoyen 1757, B2800 Zárate, Provincia de Buenos Aires, Argentina.\n\n";
        $informacion.="📱 <b>Telefono:</b> 03487 666666 / 03487 555555 / 03487 444444\n\n";
        $informacion.="🕐 <b>Horario de atencion:</b> 08:00 hs - 20:00 hs\n\n";        
        $informacion.="🔗 <b>Sitio Web</b>\nhttps://zaratesystemgroup.com.ar/\n\n";
        $informacion.="🔗 <b>INSTAGRAM</b>\nhttps://www.instagram.com/zaratesystemgroup/\n\n";
        $informacion.="🔗 <b>FACEBOOK</b>\nhttps://www.facebook.com/zarasystemgroup/\n\n";           
        $telegram->sendMessage($chatId,$informacion, 'HTML');
        $telegram->sendMessage($chatId,$SubMenu,'HTML');  
        
/* ############ INICIAR SESION ############*/        
    }elseif($text === '/IniciarSesion'){

       /* $pdfpath = 'assets/test.pdf';
        $telegram->sendDocument($chatId, new CURLFile(realpath($pdfpath)));*/

        $message = "Ingrese su <b>DNI</b>";
        $telegram->sendMessage($chatId,$message,'HTML');

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

        //COMPRUEBO SI EXISTE DNI EN TABLA USUARIO
        $SqlCheckUsuario = mysqli_query($conn, "SELECT count(*) FROM usuarios where dni = $text");
        $SqlCheckUsuariosResult = mysqli_fetch_assoc($SqlCheckUsuario);
        if($SqlCheckUsuariosResult['0'] == 0){
            $defaultMesage="SELECT count(*) FROM usuarios where dni = $text";
        } else {
            $SqlInfoUsuario = mysqli_query($conn, "SELECT nombre_apellido FROM usuarios where dni = $text");
            $SqlInfoUsuariosResult = mysqli_fetch_assoc($SqlInfoUsuario);
            $defaultMesage="Hola ".$SqlInfoUsuariosResult['nombre_apellido'].". ¿Qué desea saber? /AutoPropio /ProxVencimiento /Mantenimiento";
        }
        $telegram->sendMessage($chatId,$defaultMesage);

    }

    /* $telegram->sendMessage($chatId,"Lo que escribio el usuario es: ".$chatId." | ".$text); */
}

?>