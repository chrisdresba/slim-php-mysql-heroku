<?php

class Operacion
{
    public $idOperacion;
    public $idMesa;
    public $fecha;
    public $importe;

    public function crearOperacion()
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

    public static function obtenerOperacion($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas WHERE idMesa=:id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public function modificarOperacion()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE operaciones SET idMesa=:mesa, importe=:importe, fechaCreacion=:fecha WHERE idOperacion=:id");
        $consulta->bindValue(':mesa', $this->idMesa, PDO::PARAM_STR);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function facturacionEntreFechas($desde,$hasta,$mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT importe, fechaCreacion FROM operaciones WHERE idMesa=:mesa AND fechaCreacion BETWEEN :desde and :hasta");
        $consulta->bindValue(':desde', $desde,PDO::PARAM_STR);
        $consulta->bindValue(':hasta', $hasta,PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $mesa,PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }
}