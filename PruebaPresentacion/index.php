<?php
session_start();
//$_SESSION['FlagDNI'] = 0;

require_once("include/vendor/autoload.php");

require_once("config/conexion.php");
//require_once("models/Persona.php");

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
        $message.="<b>F</b> - /MiAuto\n";

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

        $thumbpath = 'img/TIPS/AndroidCar.png';
        $telegram->sendPhoto($chatId, new CURLFile($thumbpath),"TRUCOS ANDROID AUTO",null,$keyboard);
        sleep(3);
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

        $thumbpath = 'img/TIPS/AppleCarPlay.png';
        $telegram->sendPhoto($chatId, new CURLFile($thumbpath),"TRUCOS APPLE CAR PLAY",null,$keyboard);
        sleep(3);
        $thumbpatha = 'img/TIPS/LadoDelTanque.png';
        $telegram->sendPhoto($chatId,new CURLFile($thumbpatha),"¿Sabes qué nos está indicando esa flecha junto al símbolo del combustible?  ",null);
        sleep(3);
        $message = "Estando sentado frente al volante, en la imagen nos está indicando que para cargar combustible lo tenemos del lado izquierdo del auto.";
        $telegram->sendMessage($chatId,$message, 'HTML');
        sleep(7);
        $telegram->sendMessage($chatId,'/VerMasTIPS | '.$SubMenu,'HTML'); 

        /*$informacion="📌  Usá de forma correcta del freno de mano: <em>Solo usalo para dejarlo estacionado en una pendiente.</em>\n\n";
        $informacion.="📌  Mantené limpio el filtro del aire acondicionado: <em>Para evitar que acumule polvo y suciedad.</em>\n\n";
        $informacion.="📌  Controlá la presión de los neumáticos: <em>Va a permitir que duren más, brindándote mayor seguridad.</em>\n\n";
        $informacion.="📌  Evitá apoyar la mano en la palanca de cambio mientras manejás: <em>Crea presión que puede desgastar los componentes internos.</em>\n\n";
        $informacion.="📌  Siempre mantené un cuarto de tanque de combustible lleno: <em>No olvides considerar las distancias programadas.</em>\n\n";
        $informacion.="📌  Evitá manejar con el pie en el embrague: <em>Si patina continuamente, puede quemarse.</em>\n\n";
        $informacion.="📌  Cuidados al lavar el exterior del auto: <em>consejos para proteger la pintura y la carrocería: Lavalo al menos 2 veces al mes y hacelo con paños de microfibra.</em>\n\n";
        $informacion.="📌  Controlá el nivel de aceite: <em>Con el objetivo de prevenir fallas y daños graves.</em>\n\n";
        $informacion.="📌  Revisá el líquido refrigerante: <em>Para que la temperatura del motor se mantenga estable.</em>\n\n";
        $informacion.="📌  Chequeá los amortiguadores cada 30 mil kilómetros: <em>Esto asegura la estabilidad y confort.</em>\n\n";
        $telegram->sendMessage($chatId,$informacion,'HTML');*/

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
    }elseif($text === '/MiAuto'){

       /* $pdfpath = 'assets/test.pdf';
        $telegram->sendDocument($chatId, new CURLFile(realpath($pdfpath)));*/

        $message = "Ingrese su <b>DNI</b>";
        $telegram->sendMessage($chatId,$message,'HTML');
        $_SESSION['FlagDNI'] = 1;

    }elseif($text === '/Auto'){

        /* $pdfpath = 'assets/test.pdf';
         $telegram->sendDocument($chatId, new CURLFile(realpath($pdfpath)));*/
         $_SESSION['dni'] = $_SESSION['dni'];
         $message = "Tesla Modelo X - Año 2023 - Aún en garantía 👍 \n\n ⚙️ Próximo service: Junio 2024 o a los 10.000 KM, lo que ocurra primero. \n\n /Auto /MiPlan /MantenimientosRealizados /CargarMantenimiento ".$_SESSION['dni'];
         $telegram->sendMessage($chatId,$message,'HTML');
 
    }elseif($text === '/MiPlan'){

        /* $pdfpath = 'assets/test.pdf';
        $telegram->sendDocument($chatId, new CURLFile(realpath($pdfpath)));*/
        $_SESSION['dni'] = $_SESSION['dni'];
        $message = "Su plan es por un Tesla Modelo X \n\n <b>CUOTAS ABONADAS</b>\n 01-2023 $180.000 \n 02-2023 $180.000 \n 03-2023 $190.000 \n <b>04-2023 impaga</b> \n\n /Auto /MiPlan /MantenimientosRealizados /CargarMantenimiento ".$_SESSION['dni'];
        $telegram->sendMessage($chatId,$message,'HTML');
     
    }elseif($text === '/MantenimientosRealizados'){

        /* $pdfpath = 'assets/test.pdf';
        $telegram->sendDocument($chatId, new CURLFile(realpath($pdfpath)));*/
     
        $message = "<b>Primer Service realizdo: 03-2022</b>\n Detalle: cambio de aceite, cambio de filtro del aire, chequeo general. \n <b>Segundo Service realizado: 03-2023</b>\n Detalle: cambio de aceite, cambio de filtro del aire, chequeo general. \n\n /Auto /MiPlan /MantenimientosRealizados /CargarMantenimiento ".$_SESSION['dni'];
        $telegram->sendMessage($chatId,$message,'HTML');
     
    }elseif($text === '/CargarMantenimiento'){

        /* $pdfpath = 'assets/test.pdf';
        $telegram->sendDocument($chatId, new CURLFile(realpath($pdfpath)));*/
     
        $message = "En este espacio usted puede cargar los mantenimientos que haga en su auto para tener un historial. \n\n /Auto /MiPlan /MantenimientosRealizados /CargarMantenimiento ".$_SESSION['dni'];
        $telegram->sendMessage($chatId,$message,'HTML');
     
  
/* ############ VERMASTIPS ############*/        
    }elseif($text === '/VerMasTIPS'){

       /* $audiopath = 'assets/sample1.mp3';
        $telegram->sendAudio($chatId, new CURLFile(realpath($audiopath)));*/

        $message = "Cuando el auto está en contacto y luego de unos segundos todas las luces del tablero se apagan pero sólo queda una encendida, ahí es cuando se debe prestar atención. O cuando se está manejando y de pronto un ícono se enciende. \n\nAcá vamos a indicar el significado de algunos íconos...";
        $telegram->sendMessage($chatId,$message);
        sleep(12);
        $thumbpatha = 'img/TIPS/PisarFreno.png';
        $telegram->sendPhoto($chatId,new CURLFile($thumbpatha),"Te indica que debes pisar el freno para encender el automóvil.  ",null,$keyboard);
        sleep(4);
        $thumbpathb = 'img/TIPS/AirBag.png';
        $telegram->sendPhoto($chatId,new CURLFile($thumbpathb),"Cuando esta luz se enciende te avisa que los airbags están dañadas, por lo que debes revisarlas lo antes posible.  ",null,$keyboard);        
        sleep(4);
        $thumbpathc = 'img/TIPS/FuncionamientoMotor.png';
        $telegram->sendPhoto($chatId,new CURLFile($thumbpathc),"Este símbolo indica que la computadora del motor ha enviado un código de alerta en el diagnóstico de su funcionamiento y requiere atención.  ",null,$keyboard);        
        sleep(4);
        $telegram->sendMessage($chatId,$SubMenu,'HTML'); 

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
        /*
        if($_SESSION['FlagDNI'] === 1){
            //COMPRUEBO SI EXISTE DNI EN TABLA USUARIO
            $SqlCheckUsuario = mysqli_query($conn, "SELECT count(*) as Cantidad FROM usuario where dni = $text");
            $SqlCheckUsuariosResult = mysqli_fetch_assoc($SqlCheckUsuario);
            if($SqlCheckUsuariosResult['Cantidad'] == 0){ //SI NO EXITE DNI
                $defaultMesage="Disculpe, no existe ese DNI. /MiAuto";
            } else { //SI EXISTE DNI
                $_SESSION['dni'] = $text;
                $SqlInfoUsuario = mysqli_query($conn, "SELECT nombre_apellido FROM usuario where dni = $text");
                $SqlInfoUsuariosResult = mysqli_fetch_assoc($SqlInfoUsuario);
                $defaultMesage="Hola ".$SqlInfoUsuariosResult['nombre_apellido'].". ¿Qué desea saber? /AutoPropio /ProximoVencimiento /Mantenimientos ".$_SESSION['dni'];
            }
            $telegram->sendMessage($chatId,$defaultMesage, 'HTML');
            $telegram->sendMessage($chatId,$SubMenu,'HTML');
        }
        if($_SESSION['FlagDNI'] == 0){
            $defaultMesage="No entiendo ese comando. Puedes usar /Nosotros | /Contacto | /Videos | /TIPS | /ErroresPantalla | /MiAuto";
            $telegram->sendMessage($chatId,$defaultMesage,'HTML');
        }*/
        $_SESSION['dni'] = $text;
        $defaultMesage="Hola Juan Manuel, bienvenido! ¿Qué desea saber? /Auto /MiPlan /MantenimientosRealizados /CargarMantenimiento ".$_SESSION['dni'];
        $telegram->sendMessage($chatId,$defaultMesage,'HTML');
    }

    /* $telegram->sendMessage($chatId,"Lo que escribio el usuario es: ".$chatId." | ".$text); */
}

?>