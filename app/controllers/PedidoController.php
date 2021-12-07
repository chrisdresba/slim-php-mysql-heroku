<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Operacion.php';
require_once './db/AccesoDatos.php';

class PedidoController extends Pedido
{
  public function CargarUno(Request $request, Response $response, $args)
  {

    $parametros = $request->getParsedBody();

    try {

      if (isset($parametros['usuario']) && isset($parametros['producto']) && isset($parametros['mesa']) && isset($parametros['codigo']) && isset($parametros['unidades']) && isset($parametros['nombre'])) {
        $pedido = new Pedido();
        $pedido->idUsuario = $parametros['usuario'];
        $pedido->idProducto = $parametros['producto'];
        $pedido->idMesa = $parametros['mesa'];
        $pedido->codigoPedido = $parametros['codigo'];
        $pedido->unidades = $parametros['unidades'];
        $pedido->nombreCliente = $parametros['nombre'];
        $pedido->horaInicio = date('H:i:s');
        $pedido->estado = "pendiente";
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

  public function TraerTodos(Request $request, Response $response, $args)
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

  public function TraerPedido(Request $request, Response $response, $args)
  {
    $parametros = $request->getParsedBody();
    try {

      if (isset($parametros['codigo'])) {

        $lista = Pedido::obtenerPorCodigo($parametros['codigo']);
        $payload = json_encode(array("Pedido" => $lista));
      }
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function CambiarEstadoPedido(Request $request, Response $response, $args)
  {
    $parametros = json_decode(file_get_contents("php://input"), true);

    if (isset($parametros['codigo']) && isset($parametros['estado'])) {

      $codigo = $parametros['codigo'];
      $estado = $parametros['estado'];


      $pedido = new Pedido();
      $pedido->codigoPedido = $codigo;
      $pedido->estado = $estado;
      $pedido->modificarEstado($codigo);


      $payload = json_encode(array("mensaje" => "Estado cambiado con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "Faltan datos"));
    }


    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function DescargarCSV(Request $request, Response $response, $args)
  {

    $pedidos = Pedido::obtenerTodos();
    $path = dirname(__DIR__, 1);

    try {
      $fp = fopen($path . '/ArchivosCSV/pedidos.csv', 'w+');

      foreach ($pedidos as $pedido) {
        $array = (array)$pedido;
        fputcsv($fp, $array);
      }

      fclose($fp);
    } catch (Exception $ex) {
      $response->getBody()->write(json_encode(array("mensaje" => "error")));
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write(json_encode(array("mensaje" => "Pedidos descargados en CSV")));

    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function agregarImagenPedido(Request $request, Response $response, $args)
  {

    $codigo = $_POST['codigo'];
    $imagen = $_FILES['imagen'];

    $dir_subida = './ImagenesPedidos/';

    $pedido = new Pedido();
    $pedido->codigoPedido = $codigo;
    $pedido->foto = $dir_subida . 'pedido ' . $codigo . '.jpg';
    $pedido->fotoPedido($codigo);
    Pedido::ImagenAltaPedidos($imagen, $codigo);


    $payload = json_encode(array("mensaje" => "Se agrego la foto al pedido"));


    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }



  public function modificarEstadoMozo(Request $request, Response $response, $args)
  {

    $parametros = $request->getParsedBody();
    parse_str(file_get_contents('php://input'), $parametros);

    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);

    $usuario = AutentificadorJWT::ObtenerData($token);


    $codigoPedido = $parametros['codigo"'];
    $estado = $parametros['estado'];
    $tiempo = $parametros['tiempo'];

    $pedido = new Pedido();
    $pedido->estado = $estado;
    $pedido->codigoPedido = $codigoPedido;

    $date = new DateTime();
    $tiempo = $date->modify('+' . $tiempo . ' minute');
    $demora = $tiempo->format('H:i:s');

    switch ($usuario->tipo) {
      case 'Ba':

        if ($estado != 'listo para servir') {
          $pedido->demora = $demora;
          $pedido->modificarEstado($codigoPedido);
          $pedido->modificarDemora($codigoPedido);
        }

        $payload = json_encode(array("mensaje" => "modificado con exito pedido por Bartender"));
        break;
      case 'Cocinero':

        if ($estado != 'listo para servir') {
          $pedido->demora = $demora;
          $pedido->modificarEstado($codigoPedido);
          $pedido->modificarDemora($codigoPedido);
        }

        $payload = json_encode(array("mensaje" => "modificado con exito pedido por Cocinero"));
        break;
        break;
      case 'Cerveceros':

        if ($estado != 'listo para servir') {
          $pedido->demora = $demora;
          $pedido->modificarEstado($codigoPedido);
          $pedido->modificarDemora($codigoPedido);
        }

        $payload = json_encode(array("mensaje" => "modificado con exito pedido por Cerveceros"));
        break;

      default:
        $payload = json_encode(array("mensaje" => "No tenes autorizado modificar estado"));
        break;
    }


    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function listarPedidos(Request $request, Response $response, $args)
  {

    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);
    $usuario = AutentificadorJWT::ObtenerData($token);

    switch ($usuario->tipo) {
      case 'Socio':
        $b = Pedido::obtenerTodos();
        json_encode($b);
        break;

      case 'Mozo':
        $b = Pedido::obtenerTodos();
        json_encode($b);
        break;

      case 'Bartender':
        $b = Pedido::listarPedidoBartender();
        json_encode($b);
        break;

      case 'Cocinero':
        $b = Pedido::listarPedidoCocinero();
        json_encode($b);
        break;

      case 'Cervecero':
        $b = Pedido::listarPedidoCervecero();
        json_encode($b);
        break;

      default:
        'err';
        break;
    }

    $payload = json_encode(array("LISTA:" => $b));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function consultaDemora(Request $request, Response $response, $args)
  {

    $parametros = $request->getParsedBody();

    if (isset($parametros['codigo']) && isset($parametros['mesa'])) {


      $pedido = $parametros['codigo'];
      $array = Pedido::obtenerPedido($pedido);
      $maximo = 0;
      $tiempo = '';

      foreach ($array as $pedido) {

        $segundos = strtotime($pedido->tiempoEspera);

        if ($maximo < $segundos) {
          $tiempo = $pedido->tiempoEspera;
        }
      }

      $payload = json_encode(array("El pedido estara listo a :" => $tiempo));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    } else {
      $payload = json_encode(array("mensaje:" => "faltan ingresar datos"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
  }




  public function generarCuenta(Request $request, Response $response, $args)
  {

    $parametros = $request->getParsedBody();

    if (isset($parametros['codigo']) && isset($parametros['mesa'])) {


      $pedido = $parametros['codigo'];
      $precioFinal = 0;
      $acumulador = 0;
      $array = Pedido::obtenerPedido($pedido);

      foreach ($array as $producto) {
        $valor = Producto::obtenerProducto($producto->idProducto);
        $acumulador = ($producto->unidades * $valor->precio);
        $precioFinal += $acumulador;
      }

      $operacion = new Operacion();
      $operacion->idMesa = $parametros['mesa'];
      $operacion->importe = $precioFinal;
      $fecha = new DateTime();
      $operacion->fecha = $fecha->format('Y-m-d');
      $operacion->crearOperacion();

      $payload = json_encode(array("Importe:" => $precioFinal));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    } else {
      $payload = json_encode(array("mensaje:" => "faltan ingresar datos"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
  }


  public function pedidosFueraDeHora(Request $request, Response $response, $args)
  {

    $array = Pedido::obtenerTodos();
    $listado = array();

    foreach ($array as $pedido) {

      $segundosEspera = strtotime($pedido->tiempoEspera);
      $segundosEntrega = strtotime($pedido->horaFinalizado);

      if ($segundosEspera > $segundosEntrega) {
        array_push($listado,$pedido->idPedido);
      }
    }

    $payload = json_encode(array("Listado :" => $listado));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
