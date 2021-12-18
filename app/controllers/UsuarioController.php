<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Usuario.php';
require_once './models/Log.php';
require_once './models/Archivos.php';
require_once './db/AccesoDatos.php';
require_once './models/AutentificadorJWT.php';

class UsuarioController extends Usuario
{
  public function CargarUno(Request $request, Response $response, $args)
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

  public function TraerUno(Request $request, Response $response, $args)
  {
    try {
      $_id = $args['id'];
      $usuario = Usuario::obtenerUsuarioPorId($_id);
      $payload = json_encode($usuario);
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

      Usuario::borrarUsuario($_id);

      $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function ModificarUno(Request $request, Response $response, $args)
  {
    $parametros = json_decode(file_get_contents("php://input"), true);

    try {
      $usr = new Usuario();

      $usr->id = $parametros['id'];
      $usr->usuario = $parametros['usuario'];
      $usr->clave = $parametros['clave'];

      $usr->modificarUsuario();
      $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
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
      $lista = Usuario::obtenerTodos();
      $payload = json_encode(array("listaUsuario" => $lista));
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function Logueo(Request $request, Response $response, $args)
  {
    $parametros = $request->getParsedBody();
    try {
      if (isset($parametros['usuario']) && isset($parametros['clave'])) {
        $user = $parametros['usuario'];
        $clave = $parametros['clave'];
        $usuario = Usuario::obtenerUsuario($user);

        if (!is_null($usuario)) {
          if ($usuario->usuario == $user && $usuario->clave == $clave) {

            $log = new Log();
            $log->usuario = $usuario->usuario;
            $fecha = new DateTime();
            $log->fecha = $fecha->format("Y-m-d H:i:s");
            $log->crearLog();

            $token = AutentificadorJWT::CrearToken(array('usuario' => $usuario->usuario, 'clave' => $usuario->clave, 'tipo' => $usuario->tipo));
            $payload = json_encode(array("token" => $token));
          } else {
            $payload = json_encode(array("mensaje" => "Alguno de los datos ingresados es incorrecto. Intente nuevamente"));
          }
        }
      } else {
        $payload = json_encode(array("mensaje" => "Faltan ingresar datos"));
      }
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarEstado(Request $request, Response $response, $args)
  {

    $parametros = json_decode(file_get_contents("php://input"), true);

    try {
      $usr = new Usuario();

      $usr->idUsuario = $parametros['id'];
      $usr->estado = $parametros['estado'];

      $usr->modificarUsuarioEstado();
      $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
      
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function DescargarCSV(Request $request, Response $response)
  {
    $usuarios = Usuario::obtenerTodos();
    $path = dirname(__DIR__, 1);

    try {
      $fp = fopen($path . '/ArchivosCSV/usuarios.csv', 'w+');

      foreach ($usuarios as $user) {
        $array = (array)$user;
        fputcsv($fp, $array);
      }

      fclose($fp);

      Archivos::descargarArchivoCSV($path . '/ArchivosCSV/usuarios.csv','usuarios.csv');

    } catch (Exception $ex) {
      $response->getBody()->write(json_encode(array("mensaje" => "error")));
      return $response
        ->withHeader('Content-Type', 'application/json');
    }


    $response->getBody()->write(json_encode(array("mensaje" => "Usuarios descargados en CSV")));

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function LeerCSV(Request $request, Response $response, $args)
  {
    $entidades = array();
    $path = dirname(__DIR__, 1);

    if (file_exists($path . '/ArchivosCSV/usuarios.csv')) {

      $fp = fopen($path . '/ArchivosCSV/usuarios.csv', 'r');

      while (($linea = fgetcsv($fp, 1000, ",")) !== FALSE) {
        $nueva = Usuario::constructor($linea[0], $linea[1], $linea[2], $linea[3], $linea[4], $linea[5], $linea[6], $linea[7], $linea[8], $linea[9]);
        array_push($entidades, $nueva);
      }
      fclose($fp);

     $payload = UsuarioController::CargarDesdeLista($entidades);

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

      if(count($lista)>0){
        
        foreach($lista as $item){

        if(Usuario::obtenerUsuario($item->usuario) == false){
          $item->CrearUsuario();
          $auxiliar++;
          }
        }
        if ($auxiliar > 0) {
          $payload = json_encode(array("mensaje" => "Usuario cargado con exito"));
        } else {
          $payload = json_encode(array("mensaje" => "Los usuarios ya estaban registrados"));
        }
        
      }else{
        $payload = json_encode(array("mensaje" => "No hay usuarios para cargar"));
      }
      
    } catch (Exception $ex) {
      $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
    }

    return $payload;
  }

}
