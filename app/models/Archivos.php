<?php

class Archivos
{

    public static function descargarArchivo($path,$file)
    {
        if (file_exists($path)) {
            $size = filesize($path);
            if (function_exists('mime_content_type')) {
                $type = mime_content_type($path);
            } else if (function_exists('finfo_file')) {
                $info = finfo_open(FILEINFO_MIME);
                $type = finfo_file($info, $path);
                finfo_close($info);
            }

            if ($type == '') {
                $type = "application/force-download";
            }

            header("Content-Type: $type");
            header("Content-Disposition: attachment; filename= $file");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $size);

            readfile($path);
        } else {
            die("El archivo no existe.");
        }
    }

    public static function descargarArchivoCSV($path,$file)
    {
        if (file_exists($path)) {
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$file");
            header("Content-Type: application/csv");
            header("Content-Transfer-Encoding: binary");

            readfile($path);
        } else {
            die("El archivo no existe.");
        }
    }

}
