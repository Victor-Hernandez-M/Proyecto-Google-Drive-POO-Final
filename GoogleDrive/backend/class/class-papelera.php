<?php
    class Papelera{
        private $url;

        public function __construct($url)
        {
            $this->url = $url;
        }
        public function guardar($db){
                $resultado = $db->getReference('papelera')
                        ->getChild($_COOKIE["llave"])
                        ->getValue();
                if (in_array($this->url,$resultado["url"])){
                        return '{"mensaje": "Ya existe en papelera"}';
                }else{
                        $resultado["url"][] = $this->url;
                        $db->getReference('papelera/')
                                ->getChild($_COOKIE["llave"])
                                ->set($resultado);
                        return json_encode($resultado);
                }  
        }
        public static function eliminar($db,$indice){
                $resultadoPapelera = $db->getReference('papelera')
                        ->getChild($_COOKIE["llave"])
                        ->getValue();
                $resultadoArchivos = $db->getReference('archivos/'.$_COOKIE["llave"])
                        ->getSnapshot()
                        ->getValue();
                if (in_array($resultadoPapelera["url"][$indice],$resultadoArchivos["url"])){
                        array_splice($resultadoPapelera["url"], $indice, 1);
                }else{
                        unlink('../'.$resultadoPapelera["url"][$indice]);
                        array_splice($resultadoPapelera["url"], $indice, 1);
                }
                $db->getReference('papelera/')
                        ->getChild($_COOKIE["llave"])
                        ->set($resultadoPapelera);
                return json_encode($resultadoPapelera);
                
        }
        public static function obtenerUrls($db,$llave){
            $resultado = $db->getReference('papelera/'.$llave)
                    ->getSnapshot()
                    ->getValue();
            return json_encode($resultado);
        }
        public static function obtenerUrl($db,$indice){
                $resultado = $db->getReference('papelera')
                        ->getChild($_COOKIE["llave"])
                        ->getValue();
                $url = $resultado["url"][$indice];
                return $url;
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
?>