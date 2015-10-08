<?php
require_once 'class/Actividad.php';
require_once 'class/TipoActividad.php';
require_once 'class/UnidadNegocio.php';
require_once 'lib/Utilerias.php';
//session_start();

/* if (!isset($_SESSION['cve_usuario'])) 
  {
  header('Location:login.php');
  return;
  } */

$a = new Actividad();
$ta = new TipoActividad();
$un = new UnidadNegocio();
$count = 0;
$xAccion = "";
$txtCveActividad = 0;
$txtCveTipo = 0;
$txtCveUnidadNegocio = 0;
$txtNombre = "";
$txtDescripcion = "";
$cbxActivo = 0;
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $xAccion = test_input($_POST["xAccion"]);
    $txtCveActividad = (int) test_input($_POST["txtCveActividad"]);
    //$txtCveTipo = (int) test_input($_POST["txtCveTipo"]);
    //$txtCveUnidadNegocio = (int) test_input($_POST["txtCveUnidadNegocio"]);
    $txtNombre = test_input($_POST["txtNombre"]);
    $txtDescripcion = test_input($_POST["txtDescripcion"]);
    $cbxActivo = isset($_POST["cbxActivo"]) ? 1 : 0;

    if ($txtCveActividad != 0) {
        $a = new Actividad($txtCveActividad,$txtCveTipo,$txtCveUnidadNegocio);
    }

    if ($xAccion == 'grabar') {
        $a->setCve_tipo($txtCveTipo);
        $a->setCve_unidad_negocio($txtCveTipo);
        $a->setNombre($txtNombre);
        $a->setDescripcion($cbxActivo);
        $a->setActivo($cbxActivo);
        $count = $a->grabar();

        if ($count > 0) {
            $msg = "Los datos han sido guardados.";
        } else {
            $msg = "Ha ocurrido un imprevisto al guardar los datos";
        }
    } elseif ($xAccion == 'eliminar') {
        $count = $a->borrar();
        if($count > 0)
        { $msg = "El registro ha sido borrado con éxito"; $a = NULL;}
        else
        { $msg = "Ha ocurrido un imprevisto al borrar el registro"; $a = NULL;}
    } elseif ($xAccion == 'logout') {
        /* unset($_SESSION['cve_usuario']);
          header('Location:login.php');
          return; */
    }
}

$sql = "SELECT * FROM actividades ORDER BY cve_actividad";
$rst = UtilDB::ejecutaConsulta($sql);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Actividades | Admin</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../twbs/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="page-header">
                        <h1 id="forms">Actividades</h1>
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
                                    <label for="txtCveActividad" class="col-lg-2 control-label">ID Actividad</label>
                                    <div class="col-lg-10">
                                        <input type="hidden" id="xAccion" name="xAccion" value="0">
                                        <input type="text" class="form-control" id="txtCveActividad" name="txtCveActividad" placeholder="ID Tipo actividad" readonly value="<?php echo($a != NULL ? $a->getCve_actividad():""); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                <label for="cmbCveRito">Rito:</label>
                                <select name="cmbCveRito" id="cmbCveRito" class="form-control" placeholder="Rito">
                                    <option value="0">--------- SELECCIONE UNA OPCIÓN ---------</option>
                                    <?php
                                    $sql2 = "SELECT * FROM ritos where activo=1 ORDER BY cve_rito";
                                    $rst2 = UtilDB::ejecutaConsulta($sql2);
                                    foreach ($rst2 as $row) {
                                        echo("<option value='" . $row['cve_rito'] . "' " . ($clasf->getCve_clasificacion() != 0 ? ($clasf->getCve_rito()->getCve_rito() == $row['cve_rito'] ? "selected" : "") : "") . ">" . $row['descripcion'] . "</option>");
                                    }
                                    $rst2->closeCursor();
                                    ?>

                                </select>
                            </div>
                                <div class="form-group">
                                    <label for="txtNombre" class="col-lg-2 control-label">Nombre</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="txtNombre" name="txtNombre" placeholder="Nombre" value="<?php echo($a != NULL ? $a->getNombre():""); ?>">                                       
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtDescripcion" class="col-lg-2 control-label">Descripción</label>
                                    <div class="col-lg-10">                                        
                                        <input type="text" class="form-control" id="txtDescripcion" name="txtDescripcion" placeholder="Descripcion" value="<?php echo($a != NULL ? $a->getDescripcion():""); ?>">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="cbxActivo" name="cbxActivo" <?php echo($a != NULL ? ($a->getCve_actividad() != 0 ? ($a->getActivo() ? "checked" : "") : "checked"):""); ?>> Activo
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
                                <th>Tipo de actividad</th>
                                <th>Unidad de negocio</th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Activo</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rst as $row){?>
                            <tr>
                                <td><?php echo($row['cve_actividad']);?></td>
                                <td><?php echo($row['cve_tipo']);?></td>
                                <td><?php echo($row['cve_unidad_negocio']);?></td>
                                <td><?php echo($row['nombre']);?></td>
                                <td><?php echo($row['descripcion']);?></td>
                                <td><?php echo($row['activo'] == 1 ? "Si":"No");?></td>
                                <td><a href="javascript:void(0);" onclick="editar(<?php echo($row['cve_tipo']);?>);"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                <td><a href="javascript:void(0);" onclick="if(confirm('¿Está realmente seguro de eliminar este registro?')){eliminar(<?php echo($row['cve_tipo']);?>);}else{ return false;};"><span class="glyphicon glyphicon-erase"></span></a></td>
                            </tr>
                            <?php } $rst->closeCursor();?>
                        </tbody>
                    </table> 
                </div>
            </div>
            <?php }?>
        </div>
        <script src="../js/jQuery/jquery-1.11.3.min.js"></script>
        <script src="../twbs/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
        <script>
            function grabar()
            {
                $("#xAccion").val("grabar");
                $("#frm_captura").submit();
            }
            
            function editar(cve_actividad)
            {   $("#txtCveActividad").val(cve_actividad);
                $("#frm_captura").submit();
            }
            
            function eliminar(cve_actividad)
            {
                $("#xAccion").val("eliminar");
                $("#txtCveActividad").val(cve_actividad);
                $("#frm_captura").submit();
            }

            function limpiar()
            {
                $("#xAccion").val("");
                $("#txtCveActividad").val("0");
                $("#frm_captura").submit();
            }
        </script>
    </body>
</html>