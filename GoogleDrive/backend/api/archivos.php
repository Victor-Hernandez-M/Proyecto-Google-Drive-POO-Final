<?php
    header("Content-Type: application/json");
    include_once("../class/class-archivo.php");
    include_once("../class/class-database.php");
    include_once("../class/class-usuario.php");
    $_POST = file_get_contents('php://input');
    $database = new Database();
    switch($_SERVER['REQUEST_METHOD']){
        case 'POST':
            //Guardar
            if (Usuario::verificarAutenticacion()){
                $archivo = $_FILES["archivo"];
                Usuario::guardarArchivo($database->getDB(),'data/'.$_COOKIE["llave"].'/'.$archivo["name"]);
                $archivo = new Archivo($archivo["tmp_name"],$archivo["name"]);
                $archivo->subir();
    
            }else{
                echo '{"codigo": 401, "mensaje":"acceso no autorizado"}';
            }
            exit();
        break;
        case 'GET':
            //obtener
            if (Usuario::verificarAutenticacion()){
                if (isset($_GET['indice'])){
                    //
                }else{
                    echo Archivo::obtenerUrls($database->getDB(),$_COOKIE["llave"]);
                }
            }else{
                echo '{"codigo": 401,"mensaje": "Acceso no autorizado"}';
            }
        break;
        case 'PUT':
            //Editar
            
        break;
        case 'DELETE':
            echo Archivo::eliminar($database->getDB(),$_GET["indice"]);
        break;
    }   
?>