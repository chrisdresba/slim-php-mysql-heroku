<?php

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

    public static function PDFLogs($request, $response, $args)
    {
      $pdf = new TCPDF('P', 'cm','letter');
      $pdf->SetAuthor("Comanda", true);
      $pdf->SetFont('', '', 6);
      $pdf->SetTitle("Listado de Logs", true);
      $pdf->AddPage();
  
      $listado = Log::obtenerTodos();

      foreach($listado as $log){
        $pdf->Cell(0, 1, "id: " . $log->id . " - " . "usuario: " .$log->usuario . " - " . "fecha: " . $log->fecha , 0, 1);
      }

     $path = dirname(__DIR__, 1);

     $pdf->Output($path . '/ArchivosPDF/PDFLogs.pdf','F');
      
      $payload = json_encode(array("mensaje" => "PDF GENERADO"));
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    
    }

    
    
}

?>