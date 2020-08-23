<?php
    class Destacado{
        private $url;

        public function __construct($url){
                $this->url = $url;
        }
        public function guardar($db){
                $resultado = $db->getReference('destacados')
                        ->getChild($_COOKIE["llave"])
                        ->getValue();
                if (in_array($this->url,$resultado["url"])){
                        return '{"mensaje": "Ya existe en destacado"}';
                }else{
                        $resultado["url"][] = $this->url;
                        $db->getReference('destacados/')
                                ->getChild($_COOKIE["llave"])
                                ->set($resultado);
                        return json_encode($resultado);
                }
        }
        public static function eliminar($db,$indice){
            $resultado = $db->getReference('destacados')
                    ->getChild($_COOKIE["llave"])
                    ->getValue();
            //unlink('../'.$resultado["url"][$indice]);
            array_splice($resultado["url"], $indice, 1);
            $db->getReference('destacados/')
                    ->getChild($_COOKIE["llave"])
                    ->set($resultado);
            return json_encode($resultado);
        }
        public static function obtenerUrls($db,$llave){
            $resultado = $db->getReference('destacados/'.$llave)
                    ->getSnapshot()
                    ->getValue();
            return json_encode($resultado);
        }
        /**
         * Get the value of url
         */ 
        public function getUrl()
        {
                return $this->url;
        }

        /**
         * Set the value of url
         *
         * @return  self
         */ 
        public function setUrl($url)
        {
                $this->url = $url;

                return $this;
        }
    }
