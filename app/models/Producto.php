<?php

use Producto as GlobalProducto;

require_once './db/AccesoDatos.php';

class Producto
{
    public $idProducto;
    public $nombre;
    public $seccion;
    public $precio;
    public $fechaCarga;
    public $fechaModificacion;


    public function crearProductos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, seccion, fechaCarga, precio) VALUES (:nombre, :seccion, :fechaCarga, :precio)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':seccion', $this->seccion, PDO::PARAM_STR);
        $consulta->bindValue(':fechaCarga', $this->fechaCarga, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE idProducto=:id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }


    public function modificarProducto()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET nombre=:nombre, seccion=:seccion, fechaModificacion=:fechaModificacion, precio=:precio WHERE idProducto=:id");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':seccion', $this->seccion, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', $this->fechaModificacion, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM productos WHERE idProducto=:id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function constructor($id,$nombre,$seccion,$precio,$fechaCarga,$fechaModificacion)
    {
        $producto=new Producto();
        $producto->idProducto=$id;
        $producto->nombre=$nombre;
        $producto->seccion=$seccion;
        $producto->precio=$precio;
        $producto->fechaCarga=$fechaCarga;
        $producto->fechaModificacion=$fechaModificacion;
        return $producto;
    }

}