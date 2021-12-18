<?php
// Error Handling 
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';
require_once './db/AccesoDatos.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/ConsultasController.php';
require_once './models/AutentificadorJWT.php';
require_once './middlewares/MWAutentificar.php';
require_once './models/Log.php';
require_once './models/Mesa.php';
require_once './models/Pedido.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');
// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();


// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioController::class . ':Logueo');
});

$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->post('[/]', \UsuarioController::class . ':CargarUno')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->get('/descargar', \UsuarioController::class . ':DescargarCSV')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->get('/leerCSV', \UsuarioController::class . ':LeerCSV');
})->add(\MWAutentificar::class . ':VerificarUsuario');

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->post('[/]', \MesaController::class . ':CargarUno')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->get('/descargar', \MesaController::class . ':DescargarCSV')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->get('/leerCSV', \MesaController::class . ':LeerCSV');
  $group->get('/socios', \MesaController::class . ':listadoMesasEstados')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->put('/cerrar', \MesaController::class . ':CerrarMesa')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->put('/modificar', \MesaController::class . ':modificarEstadoMesa')->add(\MWAutentificar::class . ':VerificarTipoMozo');

})->add(\MWAutentificar::class . ':VerificarUsuario')->add(\MWAutentificar::class . ':VerificarTipoUsuario');

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->get('/listar', \PedidoController::class . ':listarPedidos');
  $group->post('/traer', \PedidoController::class . ':TraerPedido')->add(\MWAutentificar::class . ':VerificarTipoMozo');
  $group->get('/pendientes', \PedidoController::class . ':listarPedidosPendientes');
  $group->get('/enpreparacion', \PedidoController::class . ':listarPedidosEnPreparacion');
  $group->get('/cancelados', \PedidoController::class . ':TraerCancelados');
  $group->post('[/]', \PedidoController::class . ':CargarUno')->add(\MWAutentificar::class . ':VerificarTipoMozo');
  $group->post('/imagen', \PedidoController::class . ':agregarImagenPedido')->add(\MWAutentificar::class . ':VerificarTipoMozo');
  $group->get('/socios', \PedidoController::class . ':listadoPedidoDemora')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->get('/demoras', \Pedido::class . ':pedidosFueraDeHora')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->put('/modificar', \PedidoController::class . ':modificarEstadoPedido');
})->add(\MWAutentificar::class . ':VerificarUsuario');

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->post('[/]', \ProductoController::class . ':CargarUno')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->get('/descargar', \ProductoController::class . ':DescargarCSV')->add(\MWAutentificar::class . ':VerificarTipoSocio');
  $group->get('/leerCSV', \ProductoController::class . ':LeerCSV');
})->add(\MWAutentificar::class . ':VerificarUsuario')->add(\MWAutentificar::class . ':VerificarTipoUsuario');

$app->group('/pdfempleados', function (RouteCollectorProxy $group) {
  $group->get('[/]', \Log::class . ':PDFLogs')->add(\MWAutentificar::class . ':VerificarTipoSocio');
})->add(\MWAutentificar::class . ':VerificarUsuario');

$app->group('/cliente', function (RouteCollectorProxy $group) {
  $group->post('/pedido', \PedidoController::class . ':consultaDemora');
  $group->post('[/cobros]', \PedidoController::class . ':generarCuenta')->add(\MWAutentificar::class . ':VerificarTipoMozo');
});

$app->group('/encuesta', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EncuestaController::class . ':CargarUno');
});

$app->group('/consultas', function (RouteCollectorProxy $group) {
  $group->get('/mejorescomentarios', \ConsultasController::class . ':obtenerMejoresComentario');
  $group->get('/mesamasusada', \ConsultasController::class . ':obtenerMesaMasUsada');
  $group->get('/mesamenosusada', \ConsultasController::class . ':obtenerMesaMenosUsada');
  $group->get('/pedidosfueradetiempo', \ConsultasController::class . ':obtenerPedidosNoEntregadosATiempo');
  $group->get('/pedidosatiempo', \ConsultasController::class . ':obtenerPedidosEntregadosATiempo');
  $group->get('/operacionesporsector', \ConsultasController::class . ':obtenerOperacionesPorSector');
  $group->get('/operacionesporempleado', \ConsultasController::class . ':obtenerOperacionesPorEmpleado');
  $group->get('/productomasvendido', \ConsultasController::class . ':obtenerProductoMasVendido');
  $group->post('/logsempleado', \ConsultasController::class . ':obtenerLogsEmpleado');
  $group->get('/menorfactura', \ConsultasController::class . ':obtenerMenorFactura');
  $group->get('/mayorfactura', \ConsultasController::class . ':obtenerMayorFactura');
  $group->post('/facturacionporfecha', \ConsultasController::class . ':obtenerFacturacionEntreFechas');
})->add(\MWAutentificar::class . ':VerificarTipoSocio');


$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("La comanda , UTN Cristian Barraza");
    return $response;

});



$app->run();

?>