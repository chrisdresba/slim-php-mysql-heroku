<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Usuario.php';
require_once './db/AccesoDatos.php';

class UsuarioController extends Usuario
{
  public function CargarUno(Request $request,Response $response, $args)
  {
    $parametros = $request->getParsedBody();

    try {

      if (isset($parametros['nombre']) && isset($parametros['apellido']) && isset($parametros['usuario']) && isset($parametros['clave']) && isset($parametros['tipo']) && isset($parametros['sector'])) {
        $usr = new Usuario();

        $usr->nombre = $parametros['nombre'];
        $usr->apellido = $parametros['apellido'];
        $usr->usuario = $parametros['usuario'];
        $usr->clave = $parametros['clave'];
        $usr->tipo = $parametros['tipo'];
        $usr->sector = $parametros['sector'];
        $fecha = new DateTime();
        $usr->fechaAlta = $fecha->format('Y-m-d');
        $usr->estado = "Activo";
        $usr->crearUsuario();
        
        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
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
      $lista = Usuario::obtenerTodos();
      $payload = json_encode(array("listaUsuario" => $lista));
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
