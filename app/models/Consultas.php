<?php

require_once './db/AccesoDatos.php';

class Consultas
{

    //12
    public static function mejorComentario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idEncuesta, puntResto, experiencia, fecha FROM encuesta WHERE puntResto BETWEEN 8 AND 10");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    //13
    public static function mesaMasUsada()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa FROM operaciones GROUP BY idMesa ORDER BY count(*) DESC");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function mesaMenosUsada()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa FROM operaciones GROUP BY idMesa ORDER BY count(*) ASC");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    //14
    public static function pedidosNoEntregadosATiempo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE tiempoEspera < horaFinalizado");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

     //15
     public static function pedidosEntregadosATiempo()
     {
         $objAccesoDatos = AccesoDatos::obtenerInstancia();
         $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE tiempoEspera >= horaFinalizado");
         $consulta->execute();
 
         return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
     }

     //18
     public static function CantidadOperacionPorEmpleado($id)
     {
         $objAccesoDatos = AccesoDatos::obtenerInstancia();
         $consulta = $objAccesoDatos->prepararConsulta("SELECT id, id_usuario,sector,operacion, fecha FROM operaciones WHERE id_usuario=:id");
         $consulta->bindValue(':id', $id, PDO::PARAM_INT);
         $consulta->execute();
 
         return $consulta->fetchAll(PDO::FETCH_CLASS, 'Operacion');
     }

     //19
     public static function productoMasVendido()
     {
         $objAccesoDatos = AccesoDatos::obtenerInstancia();
         $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(unidades),idProducto FROM pedidos GROUP BY idProducto ORDER BY SUM(unidades) DESC");
         $consulta->execute();
 
         return $consulta->fetchAll(PDO::FETCH_OBJ);
     }

     //21
     public static function mesaMenorFactura()
     {
         $objAccesoDatos = AccesoDatos::obtenerInstancia();
         $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM operaciones p GROUP BY p.idMesa ORDER BY p.importe ASC");
         $consulta->execute();

         return $consulta->fetchAll(PDO::FETCH_OBJ);
     }

     public static function mesaMayorFactura()
     {
         $objAccesoDatos = AccesoDatos::obtenerInstancia();
         $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM operaciones p GROUP BY p.idMesa ORDER BY p.importe DESC");
         $consulta->execute();

         return $consulta->fetchAll(PDO::FETCH_OBJ);
     }

   
}

?>