<?php
    class Archivo{
        private $name;
        private $guardado;

        public function __construct(
            $name,
            $guardado
        )
        {
            $this->name = $name;
            $this->guardado = $guardado;
        }
        
        public function subir(){
            if (!file_exists('../data/'.$_COOKIE["llave"])){
                mkdir('../data/'.$_COOKIE["llave"],0777,true);
                if (file_exists('../data/'.$_COOKIE["llave"])){
                    if (move_uploaded_file($this->name,'../data/'.$_COOKIE["llave"].'/'.$this->guardado)){
                        echo '{"codigoResultado": 1, "mensaje": "Archivo guardado con exito"}';
                    }else{
                        echo '{"codigoResultado": 0, "mensaje": "No se guardo el archivo"}';
                    }
                }
            }else{
                if (move_uploaded_file($this->name,'../data/'.$_COOKIE["llave"].'/'.$this->guardado)){
                    echo '{"codigoResultado": 1, "mensaje": "Archivo guardado con exito"}';
                }else{
                    echo '{"codigoResultado": 0, "mensaje": "No se guardo el archivo"}';
                }
            }
        }
        public static function obtenerUrls($db,$llave){
            $resultado = $db->getReference('archivos/'.$llave)
                    ->getSnapshot()
                    ->getValue();
            return json_encode($resultado);
        }
        public static function agregarPapelera($db,$url){
            $resultado = $db->getReference('papelera')
                    ->getChild($_COOKIE["llave"])
                    ->getValue();
            $resultado["url"][] = $url;
            $db->getReference('papelera/')
                    ->getChild($_COOKIE["llave"])
                    ->set($resultado);
        }
        public static function eliminar($db,$indice){
            $resultado = $db->getReference('archivos')
                    ->getChild($_COOKIE["llave"])
                    ->getValue();
            //unlink('../'.$resultado["url"][$indice]);
            //Archivo::agregarPapelera($db,$resultado["url"][$indice]);
            array_splice($resultado["url"], $indice, 1);
            $db->getReference('archivos/')
                    ->getChild($_COOKIE["llave"])
                    ->set($resultado);
            return json_encode($resultado);
        }
        public static function obtenerUrl($db,$indice){
            $resultado = $db->getReference('archivos')
                    ->getChild($_COOKIE["llave"])
                    ->getValue();
            $url = $resultado["url"][$indice];
            return $url;
        }
        /**
         * Get the value of name
         */ 
        public function getName()
        {
                return $this->name;
        }

        /**
         * Set the value of name
         *
         * @return  self
         */ 
        public function setName($name)
        {
                $this->name = $name;

                return $this;
        }

        /**
         * Get the value of guardado
         */ 
        public function getGuardado()
        {
                return $this->guardado;
        }

        /**
         * Set the value of guardado
         *
         * @return  self
         */ 
        public function setGuardado($guardado)
        {
                $this->guardado = $guardado;

                return $this;
        }
    }
?>