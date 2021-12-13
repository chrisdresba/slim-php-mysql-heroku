<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Encuesta.php';
require_once './db/AccesoDatos.php';

class EncuestaController extends Encuesta
{
  public function CargarUno(Request $request,Response $response, $args)
  {
    $parametros = $request->getParsedBody();

    try {

      if (isset($parametros['mesa']) && isset($parametros['pedido']) && isset($parametros['puntuacionMesa']) && isset($parametros['puntuacionRestaurant'])
      && isset($parametros['puntuacionMozo'])&& isset($parametros['puntuacionCocinero']) && isset($parametros['experiencia'])) {

        $parametros = $request->getParsedBody();

        $encuesta = new Encuesta();
        $encuesta->codigoMesa = $parametros['mesa'];
        $encuesta->codigoPedido = $parametros['pedido'];
        $encuesta->mesa = intval($parametros['puntuacionMesa']);
        $encuesta->restaurant = intval($parametros['puntuacionRestaurant']);
        $encuesta->mozo = intval($parametros['puntuacionMozo']);
        $encuesta->cocinero = intval($parametros['puntuacionCocinero']);
        $encuesta->experiencia = $parametros['experiencia'];
        $fecha = new DateTime();
        $encuesta->fecha = $fecha->format('Y-m-d');
        $encuesta->crearEncuesta();
        $payload = json_encode(array("mensaje" => "Encuesta creada con exito"));
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
      $lista = Encuesta::obtenerTodos();
      $payload = json_encode(array("listaEncuesta" => $lista));
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  
}
