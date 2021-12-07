<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Producto.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/MWAutentificar.php';

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

  public function TraerUno(Request $request, Response $response, $args)
  {
    $_id = $args['id'];
    $usuario = Producto::obtenerProducto($_id);
    $payload = json_encode($usuario);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function BorrarUno($request, $response, $args)
  {
    $_id = $args['id'];

    Producto::borrarProducto($_id);

    $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

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


  public function DescargarCSV(Request $request, Response $response, $args)
  {

    $productos = Producto::obtenerTodos();
    $path = dirname(__DIR__, 1);

    try {
      $fp = fopen($path . '/ArchivosCSV/productos.csv', 'w+');

      foreach ($productos as $prod) {
        $array = (array)$prod;
        fputcsv($fp, $array);
      }

      fclose($fp);
    } catch (Exception $ex) {
      $response->getBody()->write(json_encode(array("mensaje" => "error")));
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write(json_encode(array("mensaje" => "Productos descargados en CSV")));

    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function LeerCSV(Request $request, Response $response, $args)
  {
    $entidades = array();
    $path = dirname(__DIR__, 1);
    $fp = fopen($path . '/ArchivosCSV/productos.csv', 'r');


    if ($fp) {

      while (($linea = fgetcsv($fp, 1000, ",")) !== FALSE) {
        $nueva = Producto::constructor($linea[0], $linea[1], $linea[2], $linea[3], $linea[4], $linea[5]);

        array_push($entidades, $nueva);
      }
      fclose($fp);

      var_dump($entidades);

      $response->getBody()->write(json_encode(array("CSV:" => "Se realizo la carga con exito")));
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    $response->getBody()->write(json_encode(array("mensaje" => "no se pudo leer el archivo")));
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
