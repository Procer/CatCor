<?php
    class Conectar{
        protected $dbh;

        protected function Conexion(){
            try{
                $conectar=$this->dbh = new PDO("mysql:host=localhost;dbname=u831978754_ejemplobot","u831978754_andercode","Andercode1");
                return $conectar;
            }catch(Exception $e){
                print "Error BD: ".$e;
                die();
            }
        }

        public function set_names(){
            return $this->dbh->query("SET NAMES 'utf8'");
        }
    }
?>