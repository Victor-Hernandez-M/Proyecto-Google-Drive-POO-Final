<?php
    require_once __DIR__.'../../../vendor/autoload.php';
    use Kreait\Firebase\Factory;    
    class Database{
        private $keyFile = '../secret/proyecto-drive-poo-7bcb4d7f59b4.json';
        private $URI = 'https://proyecto-drive-poo.firebaseio.com/';
        private $db;

        public function __construct(){
            $firebase = (new Factory)
                ->withServiceAccount($this->keyFile)
                ->withDatabaseUri($this->URI)
                ->create();

            $this->db = $firebase->getDatabase();

        }
        public function getDB(){
            return $this->db;
        }
    }
?>