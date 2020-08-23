<?php
    header("Content-Type: application/json");
    include_once("../class/class-archivo.php");
    include_once("../class/class-database.php");
    include_once("../class/class-usuario.php");
    include_once("../class/class-papelera.php");
    $_POST = file_get_contents('php://input');
    $database = new Database();
    switch($_SERVER['REQUEST_METHOD']){
        case 'POST':
            //Guardar
            if (Usuario::verificarAutenticacion()){
                if (isset($_GET["indice"])){
                    $url = Archivo::obtenerUrl($database->getDB(),$_GET["indice"]);
                    echo $url;
                    $papelera = new Papelera($url);
                    echo $papelera->guardar($database->getDB());
                    echo '{"mensaje": "Guardo"}';
                }else{
                    echo '{"mensaje": "url no encontrada"}';
                }
            }else{
                echo '{"mensaje": "No verifica"}';
            }

        break;
        case 'GET':
            //obtener
            if (Usuario::verificarAutenticacion()){
                if (isset($_GET["indice"])){
                    $url = Papelera::obtenerUrl($database->getDB(),$_GET["indice"]);
                    echo Usuario::guardarArchivo($database->getDB(),$url);
                }else{
                    echo Papelera::obtenerUrls($database->getDB(),$_COOKIE["llave"]);
                }             
            }
        break;
        case 'PUT':
            //Editar
            
        break;
        case 'DELETE':
            if (Usuario::verificarAutenticacion()){
                Papelera::eliminar($database->getDB(),$_GET["indice"]);
            }
        break;
    }   
?>