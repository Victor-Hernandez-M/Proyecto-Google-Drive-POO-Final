<?php
    header("Content-Type: application/json");
    include_once("../class/class-usuario.php");
    include_once("../class/class-database.php");
    $_POST = json_decode(file_get_contents('php://input'),true);
    $database = new Database();
    switch($_SERVER['REQUEST_METHOD']){
        case 'POST':
            //Guardar
            $usuario = new Usuario(
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['correo'],
                $_POST['contrasena'],
                $_POST["telefono"],
                $_POST["correoDeRecuperacion"],
                $_POST["cumpleanos"],
                $_POST["genero"]
            );
            echo $usuario->guardarUsuario($database->getDB());
        break;
        case 'GET':
            if (isset($_GET['codigoUsuario'])){
                echo Usuario::obtenerUsuario($database->getDB());
            }else{
                echo Usuario::obtenerUsuarios($database->getDB());
            }
        break;
        case 'PUT':
            //actualizar
            if (Usuario::verificarAutenticacion()){
                if (isset($_GET["contrasena"])){
                    echo Usuario::cambiarContrasena($database->getDB(),$_GET["contrasena"]);
                }else{
                    $usuario = new Usuario(
                        $_POST['nombre'],
                        $_POST['apellido'],
                        $_POST['correo'],
                        $_POST['contrasena'],
                        $_POST["telefono"],
                        $_POST["correoDeRecuperacion"],
                        $_POST["cumpleanos"],
                        $_POST["genero"]
                    );
                    echo $usuario->actualizarUsuario($database->getDB());
                }

            }else{
                echo '{"codigoResultado":0, "mensaje":"Acceso no autorizado"}';
            }
        break;
        case 'DELETE':
            //Eliminar
        break;
    }    
?>  