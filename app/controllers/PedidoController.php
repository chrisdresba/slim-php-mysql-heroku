<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Pedido.php';
require_once './db/AccesoDatos.php';

class PedidoController extends Pedido
{
  public function CargarUno(Request $request,Response $response, $args)
  {

    $parametros = $request->getParsedBody();

    try {

      if (isset($parametros['usuario']) && isset($parametros['producto']) && isset($parametros['mesa']) && isset($parametros['unidades']) && isset($parametros['nombre'])) {
        $pedido = new Pedido();
        $pedido->idUsuario = $parametros['usuario'];
        $pedido->idProducto = $parametros['producto'];
        $pedido->idMesa = $parametros['mesa'];
        $pedido->unidades = $parametros['unidades'];
        $pedido->nombreCliente = $parametros['nombre'];
        $pedido->horaInicio = date('H:i:s');
        $pedido->estado = "en preparacion";
        $fecha = new DateTime();
        $pedido->fecha = $fecha->format('Y-m-d');

        $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
      }
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos(Request $request,Response $response, $args)
  {
    try {
      $lista = Pedido::obtenerTodos();
      $payload = json_encode(array("listaPedidos" => $lista));
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
