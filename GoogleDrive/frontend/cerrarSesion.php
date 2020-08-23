<?php
    session_start();
    session_destroy();
    setcookie("token","",time()-1,"/");
    setcookie("nombre","",time()-1,"/");
    setcookie("apellido","",time()-1,"/");
    setcookie("correo","",time()-1,"/");

    header("Location: index.html");
?>