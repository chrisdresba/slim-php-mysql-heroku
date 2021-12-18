<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/MWAutentificar.php';

class MesaController extends Mesa
{
  public function CargarUno(Request $request, Response $response, $args)
  {
    $parametros = $request->getParsedBody();

    try {

      if (isset($parametros['codigo']) && isset($parametros['estado'])) {
        $mesa = new Mesa();
        $mesa->codigo = $parametros['codigo'];
        $mesa->estado = $parametros['estado'];
        $fecha = new DateTime();
        $mesa->fechaInicio = $fecha->format('Y-m-d');
        $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
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
      $lista = Mesa::obtenerTodos();
      $payload = json_encode(array("listaMesas" => $lista));
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
      $mesa = Mesa::obtenerMesa($_id);
      $payload = json_encode($mesa);
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

      Mesa::borrarMesa($_id);

      $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function CambiarEstado(Request $request, Response $response, $args)
  {
    $parametros = json_decode(file_get_contents("php://input"), true);
    try {

      if (isset($parametros['acceso'])) {
        if (isset($parametros['id']) && isset($parametros['estado'])) {
          $id = $parametros['id'];
          $estado = $parametros['estado'];
          $acceso = $parametros['acceso'];

          if ($acceso == "Mozo" && $estado != "cerrada") {
            $mesa = new Mesa();
            $mesa->idMesa = $id;
            $mesa->estado = $estado;
            $mesa->modificarMesa($id);
          }

          $payload = json_encode(array("mensaje" => "Estado cambiado con exito"));
        } else {
          $payload = json_encode(array("mensaje" => "Faltan datos"));
        }
      } else {
        $payload = json_encode(array("mensaje" => "Usuario no autorizado"));
      }
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function CerrarMesa(Request $request, Response $response, $args)
  {
    $parametros = json_decode(file_get_contents("php://input"), true);

    try {

      if (isset($parametros['acceso'])) {
        if (isset($parametros['id']) && isset($parametros['estado'])) {
          $id = intval($parametros['id']);
          $estado = $parametros['estado'];
          $acceso = $parametros['acceso'];

          if ($acceso == "Socio" && $estado == "Cerrada") {
            $mesa = new Mesa();
            $mesa->idMesa = $id;
            $mesa->estado = $estado;
            $mesa->modificarMesa($id);
          }

          $payload = json_encode(array("mensaje" => "Se cerro la mesa $id con exito"));
        } else {
          $payload = json_encode(array("mensaje" => "Faltan datos"));
        }
      } else {
        $payload = json_encode(array("mensaje" => "Usuario no autorizado"));
      }
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function DescargarCSV(Request $request, Response $response, $args)
  {

    $mesas = Mesa::obtenerTodos();
    $path = dirname(__DIR__, 1);

    try {
      $fp = fopen($path . '/ArchivosCSV/mesas.csv', 'w+');

      foreach ($mesas as $mesa) {
        $array = (array)$mesa;
        fputcsv($fp, $array);
      }

      fclose($fp);

      Archivos::descargarArchivoCSV($path . '/ArchivosCSV/mesas.csv','mesas.csv');

    } catch (Exception $ex) {
      $response->getBody()->write(json_encode(array("mensaje" => "error")));
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write(json_encode(array("mensaje" => "Mesas descargadas en CSV")));

    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function LeerCSV(Request $request, Response $response, $args)
  {
    $entidades = array();
    $path = dirname(__DIR__, 1);

    if (file_exists($path . '/ArchivosCSV/mesas.csv')) {
      $fp = fopen($path . '/ArchivosCSV/mesas.csv', 'r');

      while (($linea = fgetcsv($fp, 1000, ",")) !== FALSE) {
        $nueva = Mesa::constructor($linea[0], $linea[1], $linea[2], $linea[3], $linea[4]);

        array_push($entidades, $nueva);
      }
      fclose($fp);

      $payload = MesaController::CargarDesdeLista($entidades);

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

          if (Mesa::obtenerMesa($item->idMesa) == false) {
            $item->crearMesa();
            $auxiliar++;
          }
        }
        if ($auxiliar > 0) {
          $payload = json_encode(array("mensaje" => "Mesa cargada con exito"));
        } else {
          $payload = json_encode(array("mensaje" => "Las mesas ya estaban registradas"));
        }
      } else {
        $payload = json_encode(array("mensaje" => "No hay mesas para cargar"));
      }
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    return $payload;
  }


  public function modificarEstadoMesa(Request $request, Response $response, $args)
  {

    $parametros = json_decode(file_get_contents("php://input"), true);

    try {

      $header = $request->getHeaderLine('Authorization');
      $token = trim(explode("Bearer", $header)[1]);

      $usuario = AutentificadorJWT::ObtenerData($token);

      $tipo = $usuario->tipo;
      $pedido = $parametros['pedido'];
      $estado = $parametros['estado'];

      $array = Pedido::obtenerPedido($pedido);
      $items = count($array);

      if ($tipo == "Mozo") {

        foreach ($array as $a) {
          if ($a->estado == 'listo para servir') {
            $items = $items - 1;
          }
        }

        if ($items == 0) {
          $mesa = new Mesa();
          $mesa->estado = $estado;
          $mesa->modificarMesa($a->idMesa);
          $payload = json_encode(array("EstadoDeLaMesa" => $estado));
        } else {
          $payload = json_encode(array("Pedido" => "Hay productos que aun no estan para servir"));
        }

        if ($estado == 'con cliente esperando pedido') {
          $mesa = new Mesa();
          $mesa->estado = $estado;
          $mesa->modificarMesa($a->idMesa);
          $payload = json_encode(array("EstadoDeLaMesa" => $estado));
        }
      } else {
        $payload = json_encode(array("mensaje" => "No tenes autorizado modificar estado"));
      }
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function listadoMesasEstados(Request $request, Response $response, $args)
  {

    try {
      $mesas = Mesa::obtenerMesasEstados();
    } catch (Exception $ex) {
      $response->getBody()->write(json_encode(array("mensaje" => "error")));
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write(json_encode(array("Listado" => $mesas)));

    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
