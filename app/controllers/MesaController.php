<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Mesa.php';
require_once './db/AccesoDatos.php';

class MesaController extends Mesa
{
  public function CargarUno(Request $request,Response $response, $args)
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

  public function TraerTodos(Request $request,Response $response, $args)
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
}
