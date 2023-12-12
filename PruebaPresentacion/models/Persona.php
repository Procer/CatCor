<?php
    class Persona extends Conectar{
        public function get_persona($per_dni){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="select * from tm_persona where per_dni=?;";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$per_dni);
            $sql->execute();
            return $resultado= $sql->fetchAll(pdo::FETCH_ASSOC);
        }
    }
?>