<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Operacion.php';
require_once './models/Consultas.php';
require_once './models/Procedimientos.php';
require_once './db/AccesoDatos.php';

class ConsultasController
{
    public static function obtenerMejoresComentario(Request $request, Response $response, $args)
    {
        try {
            $consulta = Consultas::mejorComentario();
            $payload =  json_encode(array("resultado" => $consulta));
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public static function obtenerMesaMasUsada(Request $request, Response $response, $args)
    {
        try {
            $consulta = Consultas::mesaMasUsada();
            $payload =  json_encode(array("MesaMasUsada" => $consulta[0]->idMesa));
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public static function obtenerMesaMenosUsada(Request $request, Response $response, $args)
    {
        try {
            $consulta = Consultas::mesaMenosUsada();
            $payload =  json_encode(array("MesaMenosUsada" => $consulta[0]->idMesa));
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public static function obtenerPedidosNoEntregadosATiempo(Request $request, Response $response, $args)
    {
        try {
            $consulta = Consultas::pedidosNoEntregadosATiempo();
            $payload =  json_encode(array("ListaNoEntregadosATiempo" => $consulta));
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public static function obtenerPedidosEntregadosATiempo(Request $request, Response $response, $args)
    {
        try {
            $consulta = Consultas::pedidosEntregadosATiempo();
            $payload =  json_encode(array("ListaEntregadosATiempo" => $consulta));
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public static function obtenerOperacionesPorSector(Request $request, Response $response, $args)
    {
        try {

            $acumTragos = 0;
            $acumChoperas = 0;
            $acumCocina = 0;
            $acumCandy = 0;

            $array = Pedido::obtenerTodos();

            foreach ($array as $producto) {

                $valor = Producto::obtenerProducto($producto->idProducto);
                switch ($valor[0]->seccion) {
                    case 'Tragos':
                        $acumTragos++;
                        break;
                    case 'Choperas':
                        $acumChoperas++;
                        break;
                    case 'Cocina':
                        $acumCocina++;
                        break;
                    case 'Candy Bar':
                        $acumCandy++;
                        break;
                }
            }

            $payload = json_encode(array("Tragos" => $acumTragos, "Choperas" => $acumChoperas, "Cocina" => $acumCocina, "CandyBar" => $acumCandy));

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public static function obtenerOperacionesPorEmpleado(Request $request, Response $response, $args)
    {
        try {

            $acumTragos = 0;
            $acumChoperas = 0;
            $acumCocina = 0;
            $acumCandy = 0;


            $array = Procedimientos::obtenerTodos();
            $arrayBartender = array();
            $arrayCerveceros = array();
            $arrayCocineros = array();
            $arrayCandy = array();

            foreach ($array as $sector) {

                $valor = Producto::obtenerProducto($sector->idProducto);
                switch ($valor[0]->seccion) {
                    case 'Tragos':
                        $acumTragos++;
                        $auxiliar = true;
                        $accion = Procedimientos::obtenerProcedimiento($sector->usuario);

                        if (count($arrayBartender) > 0) {
                            foreach ($arrayBartender as $usuario) {
                                if ($usuario['usuario'] == $accion[0]->usuario) {
                                    $auxiliar = false;
                                }
                            }
                        }
                        if ($auxiliar == true) {
                            $arrayEmpleado = ["usuario" => $sector->usuario, "acciones" => count($accion)];
                            array_push($arrayBartender, $arrayEmpleado);
                        }

                        break;
                    case 'Choperas':
                        $acumChoperas++;
                        $auxiliar = true;
                        $accion = Procedimientos::obtenerProcedimiento($sector->usuario);
                        if (count($arrayCerveceros) > 0) {
                            foreach ($arrayCerveceros as $usuario) {
                                if ($usuario['usuario'] == $accion[0]->usuario) {
                                    $auxiliar = false;
                                }
                            }
                        }
                        if ($auxiliar == true) {
                            $arrayEmpleado = ["usuario" => $sector->usuario, "acciones" => count($accion)];
                            array_push($arrayCerveceros, $arrayEmpleado);
                        }
                        break;
                    case 'Cocina':
                        $acumCocina++;
                        $auxiliar = true;
                        $accion = Procedimientos::obtenerProcedimiento($sector->usuario);
                        if (count($arrayCocineros) > 0) {
                            foreach ($arrayCocineros as $usuario) {
                                if ($usuario['usuario'] == $accion[0]->usuario) { 
                                    $auxiliar = false;
                                }
                            }
                        }
                        if ($auxiliar == true) {
                            $arrayEmpleado = ["usuario" => $sector->usuario, "acciones" => count($accion)];
                            array_push($arrayCocineros, $arrayEmpleado);

                        }
                        break;
                    case 'Candy Bar':
                        $acumCandy++;
                        $auxiliar = true;
                        $accion = Procedimientos::obtenerProcedimiento($sector->usuario);
                        if (count($arrayCandy) > 0) {
                            foreach ($arrayCandy as $usuario) {
                                if ($usuario['usuario'] == $accion[0]->usuario) { 
                                    $auxiliar = false;
                                }
                            }
                        }
                        if ($auxiliar == true) {
                            $arrayEmpleado = ["usuario" => $sector->usuario, "acciones" => count($accion)];
                            array_push($arrayCandy, $arrayEmpleado);
                        }
                        break;
                }
            }

            $payload = json_encode(array("Tragos" => $acumTragos, "ListadoTragos" => $arrayBartender, "Choperas" => $acumChoperas, "ListadoCerveceros" => $arrayCerveceros, "Cocina" => $acumCocina, "ListadoCocina" => $arrayCocineros, "CandyBar" => $acumCandy, "ListadoCandy" => $arrayCandy));

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }



    public static function obtenerProductoMasVendido(Request $request, Response $response, $args)
    {
        try {
            $array = Consultas::productoMasVendido();
            $producto = Producto::obtenerProducto($array[0]->idProducto);
            $payload = json_encode(array("ProductoMasVendido" => $producto, "Lista" => $array));
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

 
    public static function obtenerLogsEmpleado(Request $request, Response $response, $args)
    {

        $parametros = $request->getParsedBody();

        try {

            if (isset($parametros['empleado'])) {
                $usuario = $parametros['empleado'];
                $array = Log::obtenerLog($usuario);
                $payload = json_encode(array("Conexiones" => $array));
            }
        } catch (Exception $ex) {
            $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response;
    }


    public static function obtenerMenorFactura(Request $request, Response $response, $args)
    {
        try {
            $array = Consultas::mesaMenorFactura();
            $payload = json_encode(array("MenosFacturado" => $array));
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public static function obtenerMayorFactura(Request $request, Response $response, $args)
    {
        try {
            $array = Consultas::mesaMayorFactura();
            $payload = json_encode(array("MasFacturado" => $array));
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }



    public static function obtenerFacturacionEntreFechas(Request $request, Response $response, $args)
    {

        $parametros = $request->getParsedBody();

        try {

            if (isset($parametros['mesa']) && isset($parametros['desde']) && isset($parametros['hasta'])) {
                $mesa = $parametros['mesa'];
                $desde = $parametros['desde'];
                $hasta = $parametros['hasta'];
                $array = Operacion::facturacionEntreFechas($desde, $hasta, $mesa);
                $payload = json_encode(array("FacturacionMesa$mesa" => $array));
            }
        } catch (Exception $ex) {
            $payload = json_encode(array("mensaje" => "Se produjo un error " . $ex->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response;
    }
}
