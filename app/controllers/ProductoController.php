<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Producto.php';
require_once './db/AccesoDatos.php';

class ProductoController extends Producto
{
  public function CargarUno(Request $request, Response $response, $args)
  {
    $parametros = $request->getParsedBody();

    try {

      if (isset($parametros['nombre']) && isset($parametros['seccion']) && isset($parametros['precio'])) {

        $parametros = $request->getParsedBody();

        $producto = new Producto();
        $producto->nombre = $parametros['nombre'];
        $producto->seccion = $parametros['seccion'];
        $fecha = new DateTime();
        $producto->fechaCarga = $fecha->format('Y-m-d');
        $producto->precio = intval($parametros['precio']);
        $producto->crearProductos();
        $payload = json_encode(array("mensaje" => "Producto creado con exito"));
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
      $lista = Producto::obtenerTodos();
      $payload = json_encode(array("listaProductos" => $lista));
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
