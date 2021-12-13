<?php

require_once './db/AccesoDatos.php';

class Procedimientos
{
    public $id;
    public $usuario;
    public $seccion;
    public $fecha;
    public $producto;

    public function crearProcedimiento()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO procedimientos (usuario,seccion,fecha,idProducto) VALUES (:usuario,:seccion, :fecha, :producto)");

        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':seccion', $this->seccion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':producto', $this->producto, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id , usuario , seccion, fecha, idProducto FROM procedimientos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Procedimientos');
    }

    public static function obtenerProcedimiento($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuario FROM procedimientos WHERE usuario=:usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }
    
    
}

?>