<?php

class Operacion
{
    public $idOperacion;
    public $idMesa;
    public $fecha;
    public $importe;

    public function crearOperaciones()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO operaciones (idMesa, importe, fechaCreacion) VALUES (:mesa, :importe, :fecha)");
        $consulta->bindValue(':mesa', $this->idMesa, PDO::PARAM_STR);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesa, importe, fecha FROM operaciones");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Operacion');
    }

}