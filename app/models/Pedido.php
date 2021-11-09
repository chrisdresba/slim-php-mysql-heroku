<?php

require_once './db/AccesoDatos.php';

class Pedido
{
    public $idPedido;
    public $idUsuario;
    public $idProducto;
    public $idMesa;
    public $unidades;
    public $nombreCliente;
    public $horaInicio;
    public $horaFinalizado;
    public $estado;
    public $fecha;
    public $foto;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idUsuario, idProducto, idMesa, unidades, nombreCliente, estado) VALUES (:usuario, :producto, :mesa, :unidades, :nombre, :estado)");
        $consulta->bindValue(':usuario', $this->idUsuario, PDO::PARAM_STR);
        $consulta->bindValue(':producto', $this->idProducto, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->idMesa, PDO::PARAM_STR);
        $consulta->bindValue(':unidades', $this->unidades, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':horaInicio', $this->horaInicio, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
       // $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idUsuario, idProducto, idMesa, unidades, nombreCliente FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

}