<?php
   /* class Conectar{
        protected $dbh;

        protected function Conexion(){
            try{
                $conectar=$this->dbh = new PDO("mysql:host=localhost;dbname=CatCorBot","zaratesy_CatCorBot","Cata1983.");
                return $conectar;
            }catch(Exception $e){
                print "Error BD: ".$e;
                die();
            }
        }

        public function set_names(){
            return $this->dbh->query("SET NAMES 'utf8'");
        }
    }*/

    $conn = mysqli_connect("localhost", "zaratesy_CatCotBot", "Cata1983.", "zaratesy_CatCorBot");    
?>