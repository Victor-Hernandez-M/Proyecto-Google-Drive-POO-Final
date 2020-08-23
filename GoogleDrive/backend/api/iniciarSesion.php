<?php
    session_start();
    header("Content-Type: application/json");
    include_once("../class/class-usuario.php");
    include_once("../class/class-database.php");
    $_POST = json_decode(file_get_contents('php://input'),true);
    $database = new Database();
    switch($_SERVER['REQUEST_METHOD']){
        case 'POST':
            //Guardar
            $usuario = Usuario:: iniciarSesion($database->getDB(),$_POST['correo'],$_POST['contrasena']);
            if ($usuario){
                //echo '{"codigoResultado":1, "mensaje":"Usuario autenticado"}';
                $usuario = json_decode($usuario,true);
                $resultado = array(
                    "codigoResultado" => 1,
                    "mensaje"=> "Usuario autenticado",
                    "token"=> sha1(uniqid(rand(),true))
                );
                $_SESSION["token"] = $resultado["token"];
                setcookie("token",$resultado["token"],time()+(60*60*24*31),"/");
                setcookie("llave",$usuario["llave"],time()+(60*60*24*31),"/");
                setcookie("nombre",$usuario["nombre"],time()+(60*60*24*31),"/");
                setcookie("apellido",$usuario["apellido"],time()+(60*60*24*31),"/");
                setcookie("correo",$usuario["correo"],time()+(60*60*24*31),"/");
                echo json_encode($resultado);
            }else{
                setcookie("token","",time()-1,"/");
                setcookie("nombre","",time()-1,"/");
                setcookie("apellido","",time()-1,"/");
                setcookie("correo","",time()-1,"/");
                echo '{"codigoResultado":0, "mensaje":"Usuario/password incorrectos"}';
            }
        break;
        case 'GET':
            if (isset($_GET['correo'])){
                echo Usuario:: verificarCorreo($database->getDB(),$_GET["correo"]);
            }else{
                //Usuario::obtenerUsuarios();
            }
        break;
        case 'PUT':
            //actualizar
        break;
        case 'DELETE':
            //Eliminar
        break;
    }    
?>  