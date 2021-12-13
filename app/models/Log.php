<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


require_once './db/AccesoDatos.php';
include_once("./TCPDF/tcpdf.php");

class Log extends TCPDF
{
    public $id;
    public $usuario;
    public $fecha;

    public function crearLog()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logs (usuario,fecha) VALUES (:usuario, :fecha)");

        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id , usuario , fecha FROM logs");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }

    public static function obtenerLog($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT fecha FROM logs WHERE usuario=:usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function PDFLogs(Request $request, Response $response, $args)
    {
        try {
            $pdf = new TCPDF('P', 'cm', 'A6');
            $pdf->SetAuthor("Comanda", true);
            $pdf->SetFont('', '', 7);
            $pdf->SetHeaderData(PDF_HEADER_LOGO);
            $path = dirname(__DIR__, 1);
            $pdf->Image($path  . '/logo.jpg', 180, 60, 15, 15, 'JPG');

            $pdf->SetTitle("Listado de Logs", true);
            $pdf->AddPage();

            $listado = Log::obtenerTodos();

            foreach ($listado as $log) {
                $pdf->Cell(0, 1, "id: " . $log->id . " - " . "usuario: " . $log->usuario . " - " . "fecha: " . $log->fecha, 0, 1);
            }

            $path = dirname(__DIR__, 1);

            $pdf->Output($path . '/ArchivosPDF/PDFLogs.pdf', 'F');

            $payload = json_encode(array("mensaje" => "PDF GENERADO"));
        } catch (Exception $ex) {
            $payload = json_encode(array("error" => $ex->getMessage()));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
