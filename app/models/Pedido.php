<?php

require_once './db/AccesoDatos.php';

class Pedido
{
    public $idPedido;
    public $idUsuario;
    public $idProducto;
    public $idMesa;
    public $codigoPedido;
    public $unidades;
    public $nombreCliente;
    public $horaInicio;
    public $horaFinalizado;
    public $estado;
    public $fecha;
    public $foto;
    public $tiempoEspera;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idUsuario, idProducto, idMesa, codigoPedido, unidades, nombreCliente, horaInicio, fecha, estado) VALUES (:usuario, :producto, :mesa, :codigo,:unidades, :nombre, :horaInicio, :fecha, :estado)");
        $consulta->bindValue(':usuario', $this->idUsuario, PDO::PARAM_INT);
        $consulta->bindValue(':producto', $this->idProducto, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->idMesa, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':unidades', $this->unidades, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':horaInicio', $this->horaInicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPorCodigo($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProducto, estado FROM pedidos WHERE codigoPedido=:codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtenerPorEstado($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE estado=:estado");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerCancelados()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE estado='cancelado'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }


    public static function obtenerPedido($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE codigoPedido=:codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public function modificarEstado($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET estado=:estado WHERE codigoPedido=:codigo AND idProducto=:producto");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':producto', $this->idProducto, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public function modificarDemora($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET tiempoEspera=:tiempoEspera WHERE codigoPedido=:codigo AND idProducto=:producto");
        $consulta->bindValue(':tiempoEspera', $this->demora, PDO::PARAM_STR);
        $consulta->bindValue(':producto', $this->idProducto, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public function asignarHoraFinalizado($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET horaFinalizado=:tiempoFinalizado WHERE codigoPedido=:codigo AND idProducto=:producto");
        $consulta->bindValue(':tiempoFinalizado', $this->horaFinalizado, PDO::PARAM_STR);
        $consulta->bindValue(':producto', $this->idProducto, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }


    public static function borrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM pedidos WHERE idPedido=:id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function fotoPedido($codigo)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET foto=:foto WHERE codigoPedido = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function ImagenAltaPedidos($archivo, $codigo)
  {
      $dir_subida = './ImagenesPedidos/';
      if (!file_exists($dir_subida)) {
          mkdir($dir_subida, 0777, true);
      }
      if (move_uploaded_file($archivo['tmp_name'], $dir_subida . 'pedido ' . $codigo . '.jpg')) {
      }
  }

  public static function listarPedidoPersonal($seccion)
  {
      $objAccesoDatos = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos U INNER JOIN productos P ON  U.idProducto = P.idProducto
       WHERE P.seccion = :seccion ");
      $consulta->bindValue(':seccion', $seccion, PDO::PARAM_STR);
      $consulta->execute();

      return $consulta->fetchAll(PDO::FETCH_OBJ);
  }

  public static function listarPedidoPersonalPendiente($seccion)
  {
      $objAccesoDatos = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos U INNER JOIN productos P ON  U.idProducto = P.idProducto
       WHERE P.seccion = :seccion AND U.estado = 'pendiente'");
      $consulta->bindValue(':seccion', $seccion, PDO::PARAM_STR);
      $consulta->execute();

      return $consulta->fetchAll(PDO::FETCH_OBJ);
  }

  public static function listarPedidoPersonalEnPreparacion($seccion)
  {
      $objAccesoDatos = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos U INNER JOIN productos P ON  U.idProducto = P.idProducto
       WHERE P.seccion = :seccion AND U.estado = 'en preparacion'");
      $consulta->bindValue(':seccion', $seccion, PDO::PARAM_STR);
      $consulta->execute();

      return $consulta->fetchAll(PDO::FETCH_OBJ);
  }


  public static function demoraPedido($codigo)
  {
      $objAccesoDatos = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDatos->prepararConsulta("SELECT tiempoEspera FROM pedidos WHERE codigoPedido = :codigo");
      $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);

      $consulta->execute();
      return $consulta->fetch(PDO::FETCH_OBJ);
  }

  public static function obtenerPedidosDemora()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPedido, tiempoEspera FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

}