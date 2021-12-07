<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

require_once './models/AutentificadorJWT.php';

class MWAutentificar
{
    public function VerificarUsuario(Request $request, RequestHandler $handler)
    {
        $response = new Response();
        $respuesta = "";

        $parametrosToken = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $parametrosToken)[1]);

        if ($token != '') {
            try {

                AutentificadorJWT::VerificarToken($token);
                $request = $request->withAttribute('token', $token);
                return $handler->handle($request);
            } catch (Exception $e) {

                $respuesta = json_encode(array('error' => $e->getMessage()));
            }
        } else {

            $respuesta = json_encode(['mensaje' => 'No se ingreso Token']);
        }

        $response->getBody()->write($respuesta);

        return $response;
    }

    public static function ComprobarUsuario(Request $request, RequestHandler $handler)
    {

        $response = new Response;
        $respuesta = "";

        $header = $request->getHeaderLine('Authorization');
        try {
            $token = trim(explode("Bearer", $header)[1]);
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        } catch (Exception $e) {
            $respuesta = json_encode(array('error' => $e->getMessage()));
        }

        $response->getBody()->write($respuesta);
        return $response;
    }

    public static function VerificarTipoUsuario(Request $request, RequestHandler $handler)
    {

        $response = new Response;
        $respuesta = "";
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        if ($token != '') {
            try {

                AutentificadorJWT::VerificarToken($token);
                $payload = AutentificadorJWT::obtenerData($token);
                $tipo = $payload->tipo;

                if ($tipo == "Mozo" || $tipo == "Socio" || $tipo == "Bartender" || $tipo == "Cocinero" || $tipo == "Cervecero") {
                    $response = $handler->handle($request);
                } else {
                    $respuesta = "Debe ser del personal";
                }
            } catch (Exception $e) {
                $respuesta = json_encode(array('error' => $e->getMessage()));
            }
        }

        $response->getBody()->write($respuesta);
        return $response;
    }

    public static function VerificarEmpleados(Request $request, RequestHandler $handler)
    {

        $response = new Response;
        $respuesta = "";
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        if ($token != '') {
            try {

                AutentificadorJWT::VerificarToken($token);
                $payload = AutentificadorJWT::obtenerData($token);
                $tipo = $payload->tipo;

                if ($tipo == "Mozo" || $tipo == "Bartender" || $tipo == "Cocinero" || $tipo == "Cerveceros") {
                    $response = $handler->handle($request);
                } else {
                    $respuesta = "Debe ser empleado";
                }
            } catch (Exception $e) {
                $respuesta = json_encode(array('error' => $e->getMessage()));
            }
        }

        $response->getBody()->write($respuesta);
        return $response;
    }

    public static function VerificarTipoMozo(Request $request, RequestHandler $handler)
    {

        $response = new Response;
        $respuesta = "";

        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        if ($token != '') {

            try {
                AutentificadorJWT::VerificarToken($token);
                $payload = AutentificadorJWT::obtenerData($token);
                $tipo = $payload->tipo;

                if ($tipo == "Mozo") {
                    $response = $handler->handle($request);
                } else {
                    $respuesta = "No es un Mozo";
                }
            } catch (Exception $e) {
                $respuesta = json_encode(array('error' => $e->getMessage()));
            }
        }
        $response->getBody()->write($respuesta);
        return $response;
    }

    public static function VerificarTipoSocio(Request $request, RequestHandler $handler)
    {

        $response = new Response;
        $respuesta = "";

        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        if ($token != '') {

            try {
                AutentificadorJWT::VerificarToken($token);
                $payload = AutentificadorJWT::obtenerData($token);
                $tipo = $payload->tipo;

                if ($tipo == "Socio") {
                    $response = $handler->handle($request);
                } else {
                    $respuesta = "No es un Socio";
                }
            } catch (Exception $e) {
                $respuesta = json_encode(array('error' => $e->getMessage()));
            }
        }
        $response->getBody()->write($respuesta);
        return $response;
    }

}
