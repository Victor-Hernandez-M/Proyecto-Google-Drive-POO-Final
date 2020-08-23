<?php
    class Usuario{
        private $nombre;
        private $apellido;
        private $correo;
        private $contrasena;
        private $telefono;
        private $correoDeRecuperacion;
        private $cumpleanos;
        private $genero;

        
        public function __construct(
                $nombre,
                $apellido,
                $correo,
                $contrasena,
                $telefono,
                $correoDeRecuperacion,
                $cumpleanos,
                $genero
        ){
                $this->nombre = $nombre;
                $this->apellido = $apellido;
                $this->correo = $correo;
                $this->contrasena = $contrasena;
                $this->telefono = $telefono;
                $this->correoDeRecuperacion = $correoDeRecuperacion;
                $this->cumpleanos = $cumpleanos;
                $this->genero = $genero;
		}
		
        public function guardarUsuario($db){
                $resultado = $db->getReference('usuarios')
                        ->push($this->getData());
                if ($resultado->getKey() != null){
                        return '{"mensaje": "registro almacenado","key": "'.$resultado->getKey().'","codigoResultado": 1}';
                }else{
                        return '{"mensaje": "error al almacenar registro","codigoResultado": 0}';
                }
        }
        public static function obtenerUsuario($db){
                $resultado = $db->getReference('usuarios')
                        ->getChild($_COOKIE["llave"])
                        ->getValue();
                return json_encode($resultado);
        }
        public function actualizarUsuario($db){
                $resultado = $db->getReference('usuarios')
                        ->getChild($_COOKIE["llave"])
                        ->set($this->getData());
                setcookie("nombre",$resultado["nombre"],time()+(60*60*24*31),"/");
                setcookie("apellido",$resultado["apellido"],time()+(60*60*24*31),"/");
                setcookie("correo",$resultado["correo"],time()+(60*60*24*31),"/");
                return json_encode($resultado);

        }
        public static function obtenerUsuarios($db){
                $resultado = $db->getReference('usuarios')
                        ->getSnapshot()
                        ->getValue();
                return json_encode($resultado);
        }
        public static function verificarCorreo($db,$correo){
                $resultado = $db->getReference('usuarios')
                        ->orderByChild('correo')
                        ->equalTo($correo)
                        ->getSnapshot()
                        ->getValue();
                if (sizeof($resultado)==0){
                        $resultado = array(
                                "codigoResultado" => 0,
                                "mensaje"=> "Usuario no encontrado"
                        );
                }else{
                        $resultado = array(
                                "codigoResultado" => 1,
                                "mensaje"=> "Usuario autenticado"
                            );
                }
                return json_encode($resultado);
        }

        public function getData(){
                $resultado["nombre"] = $this->nombre;
                $resultado["apellido"] = $this->apellido;
                $resultado["correo"] = $this->correo;
                $resultado["contrasena"] = password_hash($this->contrasena,PASSWORD_DEFAULT);
                $resultado["telefono"] = $this->telefono;
                $resultado["correoDeRecuperacion"] = $this->correoDeRecuperacion;
                $resultado["cumpleanos"] = $this->cumpleanos;
                $resultado["genero"] = $this->genero;
                $resultado["archivos"] = [];
                return $resultado;
        }
        public static function iniciarSesion($db,$correo,$contrasena){
                $resultado = $db->getReference('usuarios')
                        ->orderByChild('correo')
                        ->equalTo($correo)
                        ->getSnapshot()
                        ->getValue();
                $llave = array_key_first($resultado);
                $valido = password_verify($contrasena,$resultado[$llave]["contrasena"]);
                
                $respuesta["valido"] = $valido==1?true:false;
                if ($respuesta["valido"]){
                        $respuesta["llave"] = $llave;
                        $respuesta["correo"] = $resultado[$llave]["correo"];
                        $respuesta["nombre"] = $resultado[$llave]["nombre"];
                        $respuesta["apellido"] = $resultado[$llave]["apellido"];
                        /*
                        setcookie("nombre",$respuesta["nombre"],time()+(60*60*24*31),"/");
                        setcookie("apellido",$respuesta["apellido"],time()+(60*60*24*31),"/");
                        setcookie("correo",$respuesta["correo"],time()+(60*60*24*31),"/");
                        */
                        return json_encode($respuesta);
                }else{
                        return null;
                }
                
        }
        public static function verificarAutenticacion(){
                session_start();
                if (!isset($_COOKIE['llave'])){
                        return false;
                }
                if($_SESSION["token"] == $_COOKIE["token"]){
                        return true;
                }else{
                        return false;
                }
        }
        public static function guardarArchivo($db,$url){
                /*
                $editar = Usuario::obtenerUsuario($db,$_COOKIE["llave"]);
                $editar = json_decode($editar,true);
                $editar["archivos"][] = $url;
                $db->getReference('usuarios/')
                        ->getChild($_COOKIE["llave"])
                        ->set($editar);
                echo '{"mensaje": "url guardada"}';
                */
                $resultado = $db->getReference('archivos')
                        ->getChild($_COOKIE["llave"])
                        ->getValue();
                $resultado["url"][] = $url;
                $db->getReference('archivos/')
                        ->getChild($_COOKIE["llave"])
                        ->set($resultado);
        }
        public static function cambiarContrasena($db,$contrasena){
                $resultado = $db->getReference('usuarios')
                        ->getChild($_COOKIE["llave"])
                        ->getValue();
                $resultado["contrasena"] = password_hash($contrasena,PASSWORD_DEFAULT);
                $db->getReference('usuarios/')
                        ->getChild($_COOKIE["llave"])
                        ->set($resultado);
                return '{"mensaje":"guardado con exito"}';
        }
        /**
         * Get the value of nombre
         */ 
        public function getNombre()
        {
                return $this->nombre;
        }

        /**
         * Set the value of nombre
         *
         * @return  self
         */ 
        public function setNombre($nombre)
        {
                $this->nombre = $nombre;

                return $this;
        }

        /**
         * Get the value of apellido
         */ 
        public function getApellido()
        {
                return $this->apellido;
        }

        /**
         * Set the value of apellido
         *
         * @return  self
         */ 
        public function setApellido($apellido)
        {
                $this->apellido = $apellido;

                return $this;
        }

        /**
         * Get the value of correo
         */ 
        public function getCorreo()
        {
                return $this->correo;
        }

        /**
         * Set the value of correo
         *
         * @return  self
         */ 
        public function setCorreo($correo)
        {
                $this->correo = $correo;

                return $this;
        }

        /**
         * Get the value of contrasena
         */ 
        public function getContrasena()
        {
                return $this->contrasena;
        }

        /**
         * Set the value of contrasena
         *
         * @return  self
         */ 
        public function setContrasena($contrasena)
        {
                $this->contrasena = $contrasena;

                return $this;
        }

        /**
         * Get the value of telefono
         */ 
        public function getTelefono()
        {
                return $this->telefono;
        }

        /**
         * Set the value of telefono
         *
         * @return  self
         */ 
        public function setTelefono($telefono)
        {
                $this->telefono = $telefono;

                return $this;
        }

        /**
         * Get the value of correoDeRecuperacion
         */ 
        public function getCorreoDeRecuperacion()
        {
                return $this->correoDeRecuperacion;
        }

        /**
         * Set the value of correoDeRecuperacion
         *
         * @return  self
         */ 
        public function setCorreoDeRecuperacion($correoDeRecuperacion)
        {
                $this->correoDeRecuperacion = $correoDeRecuperacion;

                return $this;
        }

        /**
         * Get the value of cumpleanos
         */ 
        public function getCumpleanos()
        {
                return $this->cumpleanos;
        }

        /**
         * Set the value of cumpleanos
         *
         * @return  self
         */ 
        public function setCumpleanos($cumpleanos)
        {
                $this->cumpleanos = $cumpleanos;

                return $this;
        }

        /**
         * Get the value of genero
         */ 
        public function getGenero()
        {
                return $this->genero;
        }

        /**
         * Set the value of genero
         *
         * @return  self
         */ 
        public function setGenero($genero)
        {
                $this->genero = $genero;

                return $this;
        }
    }
?>