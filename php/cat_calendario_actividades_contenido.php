<?php
require_once 'class/CalendarioActividadContenido.php';
require_once 'class/CalendarioActividad.php';
require_once 'class/TipoContenido.php';
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
        $cac = new CalendarioActividadContenido($txtCveCalendario);
    }

    if ($xAccion == 'grabar') {
        $cac->setCve_calendario(new CalendarioActividad($txtCveCalendario));
        $cac->setCve_tipo_contenido(new TipoContenido($txtCveTipoContenido)); 
        $cac->setActivo($cbxActivo);
        $count = $cac->grabar();

        if ($count > 0) {
            $msg = "Los datos han sido guardados.";
        } else {
            $msg = "Ha ocurrido un imprevisto al guardar los datos";
        }
    } elseif ($xAccion == 'eliminar') {
        $count = $cac->borrar();
        if($count > 0)
        { $msg = "El registro ha sido borrado con éxito"; $cac = NULL;
            
        }
        else
        { 
            $msg = "Ha ocurrido un imprevisto al borrar el registro"; $cac = NULL;
            
        }
    } elseif ($xAccion == 'logout') {
        /* unset($_SESSION['cve_usuario']);
          header('Location:login.php');
          return; */
    }
}

$sql = "SELECT cac.cve_actividad_contenido,cac.cve_calendario,cac.cve_tipo_contenido, ";
$sql.= "concat(a.nombre,' Fecha inicio: ',DATE_FORMAT(fecha_inicio,'%d/%m/%Y'),' Fecha fin:',DATE_FORMAT(fecha_fin,'%d/%m/%Y'))as actividad,cac.activo ";
$sql.= "FROM calendario_actividades_contenido as cac ";
$sql.= "INNER JOIN calendario_actividades ca on ca.cve_calendario=cac.cve_calendario ";
$sql.= "INNER JOIN actividades a on a.cve_actividad=ca.cve_actividad ";
$sql.= "INNER JOIN tipos_contenido tc on tc.cve_tipo_contenido=cac.cve_actividad_contenido ";

$rst = UtilDB::ejecutaConsulta($sql);
?>


<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Grupos HISA | Calendario actividades contenido</title>
        <meta charset="utf-8">
        <link rel="shortcut icon" type="image/png" href="../img/favicon.png"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../js/jquery-ui-1.11.4/jquery-ui.min.css" rel="stylesheet"/>
        <link href="../twbs/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="page-header">
                        <h1 id="forms">Calendario actividades contenido</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3" style="<?php echo($msg != "" && $count > 0 ? "display:block" : "display:none"); ?>">
                    <div class="bs-component">
                        <div class="alert alert-dismissible alert-success">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <p><strong>Éxito!</strong> <?php echo($msg);?><p>
                        </div>
                        <div id="source-button" class="btn btn-primary btn-xs" style="display: none;">&lt; &gt;</div>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-3" style="<?php echo($msg != "" && $count == 0 ? "display:block" : "display:none"); ?>">
                    <div class="bs-component">
                        <div class="alert alert-dismissible alert-danger">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <p><strong>Error</strong> <?php echo($msg);?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">                
                <div class="col-md-6 col-md-offset-3">
                    <div class="well bs-component">
                        <form id="frm_captura" name="frm_captura" class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <fieldset>
                                <legend>Captura</legend>
                                <div class="form-group">
                                    <label for="txtCveActividadContenido" class="col-lg-4 control-label">ID</label>
                                    <div class="col-lg-8">
                                        <input type="hidden" id="xAccion" name="xAccion" value="0">
                                        <input type="text" class="form-control" id="txtCveActividadContenido" name="txtCveActividadContenido" placeholder="ID Calendario actividad contenido" readonly value="<?php echo($cac != NULL ? $cac->getCve_calendario():""); ?>">
                                    </div>
                                 </div>
                                <div class="form-group">
                                <label for="txtCveCalendario">Calendario:</label>
                                    <select name="txtCveCalendario" id="txtCveCalendario" class="form-control" placeholder="Calendario">
                                        <option value="0">--------- SELECCIONE UNA OPCIÓN ---------</option>
                                        <?php
                                        //echo($ca);
                                        $sql2 = "SELECT cac.cve_actividad_contenido,cac.cve_calendario,cac.cve_tipo_contenido,concat(a.nombre,' Fecha inicio: ',DATE_FORMAT(fecha_inicio,'%d/%m/%Y'),' Fecha fin:',DATE_FORMAT(fecha_fin,'%d/%m/%Y'))as actividad,cac.activo ";
                                        $sql2.= "FROM calendario_actividades_contenido as cac ";
                                        $sql2.= "INNER JOIN calendario_actividades ca on ca.cve_calendario=cac.cve_calendario ";
                                        $sql2.= "INNER JOIN actividades a on a.cve_actividad=ca.cve_actividad ";
                                        $sql2.= "WHERE cac.activo=1 ";
                                        $sql2 .= "ORDER BY a.nombre ";

                                        $rst2 = UtilDB::ejecutaConsulta($sql2);
                                        foreach ($rst2 as $row) {
                                            echo("<option value='" . $row['cve_calendario'] . "' " . ($cac != NULL ? ($cac->getCve_calendario() != NULL ? ($cac->getCve_calendario()->getCve_calendario() == $row['cve_calendario'] ? "selected" : "") : ""):"") . ">" . $row['actividad'] . "</option>");
                                        }
                                        $rst2->closeCursor();
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                <label for="txtCveTipoContenido">Calendario:</label>
                                   <select name="txtCveTipoContenido" id="txtCveTipoContenido" class="form-control" placeholder="TipoContenido">
                                       <option value="0">--------- SELECCIONE UNA OPCIÓN ---------</option>
                                       <?php
                                       //echo($ca);
                                       $sql2 = "SELECT * FROM `tipos_contenido` WHERE activo=1 ORDER BY nombre ";

                                       $rst2 = UtilDB::ejecutaConsulta($sql2);
                                       foreach ($rst2 as $row) {
                                           echo("<option value='" . $row['cve_tipo_contenido'] . "' " . ($cac != NULL ? ($cac->getCve_tipo_contenido() != NULL ? ($cac->getCve_tipo_contenido()->getCve_tipo_contenido() == $row['cve_tipo_contenido'] ? "selected" : "") : ""):"") . ">" . $row['nombre'] . "</option>");
                                       }
                                       $rst2->closeCursor();
                                       ?>
                                   </select>
                               </div>
                               <div class="form-group">
                                    <label for="txtURL" class="col-lg-2 control-label">URL</label>
                                    <div class="col-lg-10">                                        
                                        <input type="text" class="form-control" id="txtURL" name="txtURL" placeholder="URL" value="<?php echo($cac != NULL ? $cac->getUrl():""); ?>">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="cbxActivo" name="cbxActivo" <?php echo($cac != NULL ? ($cac->getCve_actividad_contenido() != 0 ? ($cac->getActivo() ? "checked" : "") : "checked"):""); ?>> Activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <button type="button" class="btn btn-default" onclick="limpiar();">Cancel</button>
                                        <button type="button" class="btn btn-primary" onclick="grabar();">Enviar</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
             <?php if($rst->rowCount() > 0){?>
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1 id="tables">Listado</h1>
                </div>
                <div class="bs-component">
                    <table class="table table-striped table-hover ">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Calendario</th>
                                <th>Tipo contenido</th>
                                <th>URL</th>
                                <th>Activo</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rst as $row){?>
                           
                            <?php } $rst->closeCursor();?>
                        </tbody>
                    </table> 
                </div>
            </div>
            <?php }?>
        </div>
    </body>
</html>
