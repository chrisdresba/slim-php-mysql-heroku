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
    try {

      $_id = $args['id'];
      $producto = Producto::obtenerProducto($_id);
      $payload = json_encode($producto);
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function BorrarUno(Request $request, Response $response, $args)
  {
    try {
      $_id = $args['id'];

      Producto::borrarProducto($_id);

      $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
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

      Archivos::descargarArchivoCSV($path . '/ArchivosCSV/productos.csv','productos.csv');

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

    if (file_exists($path . '/ArchivosCSV/productos.csv')) {

      $fp = fopen($path . '/ArchivosCSV/productos.csv', 'r');

      while (($linea = fgetcsv($fp, 1000, ",")) !== FALSE) {
        $nueva = Producto::constructor($linea[0], $linea[1], $linea[2], $linea[3], $linea[4], $linea[5]);

        array_push($entidades, $nueva);
      }
      fclose($fp);

      $payload = ProductoController::CargarDesdeLista($entidades);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    $response->getBody()->write(json_encode(array("mensaje" => "no se pudo leer el archivo")));
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public static function CargarDesdeLista($lista)
  {
    try {
      $auxiliar = 0;
      if (count($lista) > 0) {

        foreach ($lista as $item) {

          if (Producto::obtenerProducto($item->idProducto) == false) {
            $item->crearProductos();
            $auxiliar++;
          }
        }
        if ($auxiliar > 0) {
          $payload = json_encode(array("mensaje" => "Producto cargado con exito"));
        } else {
          $payload = json_encode(array("mensaje" => "Los productos ya estaban registrados"));
        }
      } else {
        $payload = json_encode(array("mensaje" => "No hay productos para cargar"));
      }
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    return $payload;
  }
}
