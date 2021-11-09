<?php

require_once './db/AccesoDatos.php';

class Producto
{
    public $idProducto;
    public $nombre;
    public $seccion;
    public $fechaCarga;
    public $fechaModificacion;
    public $precio;

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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre, seccion, precio FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

}