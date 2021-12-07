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

  public function CambiarEstado(Request $request, Response $response, $args)
  {
    $parametros = json_decode(file_get_contents("php://input"), true);

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

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function CerrarMesa(Request $request, Response $response, $args)
  {
   $parametros = json_decode(file_get_contents("php://input"), true);

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

        $payload = json_encode(array("mensaje" => "Estado cambiado con exito"));
      } else {
        $payload = json_encode(array("mensaje" => "Faltan datos"));
      }
    } else {
      $payload = json_encode(array("mensaje" => "Usuario no autorizado"));
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
      $entidades = Array();
      $path = dirname(__DIR__, 1);
      $fp = fopen($path . '/ArchivosCSV/mesas.csv', 'r');
 

      if($fp){
  
        while (($linea = fgetcsv($fp,1000,",")) !== FALSE)
            {     
            $nueva=Mesa::constructor($linea[0],$linea[1],$linea[2],$linea[3],$linea[4]);
        
            array_push($entidades,$nueva);
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


  public function modificarEstadoMesa(Request $request, Response $response, $args)
  {

    $parametros = $request->getParsedBody();
    parse_str(file_get_contents('php://input'), $parametros);

    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);

    $usuario = AutentificadorJWT::ObtenerData($token);

    $tipo = $parametros['tipo'];
    $estado = $parametros['estado'];
   
    $array = Pedido::obtenerTodos();

       if($tipo == "Mozo"){

        foreach($array as $a){

        if ($a->estado == 'listo para servir') {

          $mesa = new Mesa();
          $mesa->estado = $estado; 
          $mesa->modificarMesa($a->idMesa);
        }

        }
      }else{
        $payload = json_encode(array("mensaje" => "No tenes autorizado modificar estado"));
      } 

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

   

}
