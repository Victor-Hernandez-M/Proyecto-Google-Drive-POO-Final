<?php
    header("Content-Type: application/json");
    include_once("../class/class-archivo.php");
    include_once("../class/class-destacado.php");
    include_once("../class/class-database.php");
    include_once("../class/class-usuario.php");
    $_POST = file_get_contents('php://input');
    $database = new Database();
    switch($_SERVER['REQUEST_METHOD']){
        case 'POST':
            //Guardar
            if (Usuario::verificarAutenticacion()){
                if (isset($_GET["indice"])){
                    $url = Archivo::obtenerUrl($database->getDB(),$_GET["indice"]);
                    $destacados = new Destacado($url);
                    echo $destacados->guardar($database->getDB());
                }else{
                    echo '{"mensaje": "url no encontrada"}';
                }
            }
        break;
        case 'GET':
            //obtener
            if (Usuario::verificarAutenticacion()){
                echo Destacado::obtenerUrls($database->getDB(),$_COOKIE["llave"]);
            }
        break;
        case 'PUT':
            //Editar
        break;
        case 'DELETE':
            if (Usuario::verificarAutenticacion()){
                Destacado::eliminar($database->getDB(),$_GET["indice"]);
            }
        break;
    }   
?>