<?php
require_once 'class/CalendarioActividad.php';
require_once 'class/TipoContenido.php';
require_once 'class/CalendarioActividadContenido.php';
require_once 'lib/Utilerias.php';
//session_start();

/* if (!isset($_SESSION['cve_usuario'])) 
  {
  header('Location:login.php');
  return;
  } */

$cac = new CalendarioActividadContenido();
$count = 0;
$xAccion = "";
$txtCveActividadContenido = 0;
$txtCveCalendario=0;
$txtCveTipoContenido = 0;
$txtURL = "";
$cbxActivo = 0;
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $xAccion = test_input($_POST["xAccion"]);
    $txtCveActividadContenido = (int) test_input($_POST["txtCveActividadContenido"]);
    $txtCveCalendario = (int) test_input($_POST["txtCveCalendario"]);
    $txtCveTipoContenido = (int) test_input($_POST["txtCveTipoContenido"]);  
    $txtURL = test_input($_POST["txtURL"]);
    $cbxActivo = isset($_POST["cbxActivo"]) ? 1 : 0;

    if ($txtCveActividadContenido != 0) {
        $ca = new CalendarioActividadContenido($txtCveActividadContenido);
    }

    if ($xAccion == 'grabar') {
        $ca->setCve_tipo(new TipoActividad($txtCveTipo));
        $ca->setNombre($txtNombre);
        $ca->setDescripcion($txtDescripcion);
        $ca->setActivo($cbxActivo);
        $count = $a->grabar();

        if ($count > 0) {
            $msg = "Los datos han sido guardados.";
        } else {
            $msg = "Ha ocurrido un imprevisto al guardar los datos";
        }
    } elseif ($xAccion == 'eliminar') {
        $count = $a->borrar();
        if($count > 0)
        { $msg = "El registro ha sido borrado con éxito"; $a = NULL;
            
        }
        else
        { 
            $msg = "Ha ocurrido un imprevisto al borrar el registro"; $a = NULL;
            
        }
    } elseif ($xAccion == 'logout') {
        /* unset($_SESSION['cve_usuario']);
          header('Location:login.php');
          return; */
    }
}

$sql = "SELECT a.cve_actividad, a.cve_tipo, ta.nombre as tipo_actividad, a.nombre as actividad, a.descripcion, a.activo ";
$sql.= "FROM actividades a ";
$sql.= "INNER JOIN tipos_actividades ta on ta.cve_tipo=a.cve_tipo ";
$sql.= "ORDER BY cve_actividad";

$rst = UtilDB::ejecutaConsulta($sql);
?>


<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        ?>
    </body>
</html>
