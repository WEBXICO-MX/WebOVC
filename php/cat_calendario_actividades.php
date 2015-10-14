<?php
require_once 'class/CalendarioActividad.php';
require_once 'lib/Utilerias.php';
//session_start();

/* if (!isset($_SESSION['cve_usuario'])) 
  {
  header('Location:login.php');
  return;
  } */

$ca = new CalendarioActividad();
$count = 0;
$xAccion = "";
$txtCveCalendario = 0;
$txtCveActividad = 0;
$txtFechaInicio = "";
$txtFechaFin = "";
$txtLugar = "";
$txtEstado = "";
$txtMunicipio = "";
$txtImagenPortada = "";
$txtPrecio = 0.0;
$txtCupoMaximo = 0;
$txtObservaciones = "";
$cbxActivo = 0;
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $xAccion = test_input($_POST["xAccion"]);
    $txtCveCalendario = (int) test_input($_POST["txtCveCalendario"]);
    $txtCveActividad = (int) test_input($_POST["txtCveActividad"]);
    $txtFechaInicio = test_input($_POST["FechaInicio"]);
    $txtFechaFin = test_input($_POST["txtFechaFin"]);
    $txtLugar = test_input($_POST["txtLugar"]);
    $txtEstado = test_input($_POST["txtEstado"]);
    $txtMunicipio = test_input($_POST["txtMunicipio"]);
    $txtImagenPortada = test_input($_POST["txtImagenPortada"]);
    $txtPrecio = test_input($_POST["txtPrecio"]);
    $txtCupoMaximo = test_input($_POST["txtCupoMaximo"]);
    $txtObservaciones = test_input($_POST["txtObservaciones"]);
    $cbxActivo = isset($_POST["cbxActivo"]) ? 1 : 0;

    if ($txtCveCalendario != 0) {
        $ca = new CalendarioActividad($txtCveCalendario);
    }

    if ($xAccion == 'grabar') {
        $ca->setCve_actividad($txtCveActividad);
        $ca->setFecha_inicio($txtFechaInicio);
        $ca->setFecha_fin($txtFechaFin);
        $ca->setLugar($txtLugar);
        $ca->setCve_estado($txtEstado);
        $ca->setCve_municipio($txtMunicipio);
        $ca->setImagen_portada($txtImagenPortada);
        $ca->setPrecio($txtPrecio);
        $ca->setCupo_maximo($txtCupoMaximo);
        $ca->setObservaciones($txtObservaciones);
        $ca->setActivo($cbxActivo);
        $count = $ca->grabar();

        if ($count > 0) {
            $msg = "Los datos han sido guardados.";
        } else {
            $msg = "Ha ocurrido un imprevisto al guardar los datos";
        }
    } elseif ($xAccion == 'eliminar') {
        $count = $ca->borrar();
        if($count > 0)
        { $msg = "El registro ha sido borrado con éxito"; $ca = NULL;
            
        }
        else
        { 
            $msg = "Ha ocurrido un imprevisto al borrar el registro"; $ca = NULL;
            
        }
    } elseif ($xAccion == 'logout') {
        /* unset($_SESSION['cve_usuario']);
          header('Location:login.php');
          return; */
    }
}

$sql = "SELECT ca.cve_calendario,a.nombre AS actividad,ca.fecha_inicio,ca.fecha_fin,ca.lugar,e.nombre AS estado,m.nombre AS municipio,ca.imagen_portada,ca.precio,ca.cupo_maximo,ca.observaciones,ca.fecha_alta,ca.activo ";
$sql .= "FROM calendario_actividades AS ca ";
$sql .= "INNER JOIN actividades AS a ON a.cve_actividad = ca.cve_actividad ";
$sql .= "INNER JOIN estados AS e ON e.cve_estado = ca.cve_estado ";
$sql .= "INNER JOIN municipios AS m ON m.cve_municipio = ca.cve_municipio AND m.cve_estado = ca.cve_estado";

$rst = UtilDB::ejecutaConsulta($sql);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Calendario actividades | Admin</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../twbs/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="page-header">
                        <h1 id="forms">Calendario actividades</h1>
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
                                    <label for="txtCveCalendario" class="col-lg-2 control-label">ID Calendario actividad</label>
                                    <div class="col-lg-10">
                                        <input type="hidden" id="xAccion" name="xAccion" value="0">
                                        <input type="text" class="form-control" id="txtCveCalendario" name="txtCveCalendario" placeholder="ID Calendario actividades" readonly value="<?php echo($ca != NULL ? $ca->getCve_calendario():""); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                <label for="txtCveActividad">Actividad:</label>
                                    <select name="txtCveActividad" id="txtCveActividad" class="form-control" placeholder="Actividad">
                                        <option value="0">--------- SELECCIONE UNA OPCIÓN ---------</option>
                                        <?php
                                        //echo($ca);
                                        $sql2 = "SELECT * FROM actividades where activo=1 ORDER BY nombre";
                                        $rst2 = UtilDB::ejecutaConsulta($sql2);
                                        foreach ($rst2 as $row) {
                                            echo("<option value='" . $row['cve_actividad'] . "' " . ($ca != NULL ? ($ca->getCve_actividad() != 0 ? ($ca->getCve_actividad() == $row['cve_actividad'] ? "selected" : "") : ""):"") . ">" . $row['nombre'] . "</option>");
                                        }
                                        $rst2->closeCursor();
                                        ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="txtFechaInicio" class="col-lg-2 control-label">Fecha inicio</label>
                                    <div class="col-lg-10">                                        
                                        <input type="text" class="form-control" id="txtFechaInicio" name="txtFechaInicio" placeholder="Fecha inicio" value="<?php echo($ca != NULL ? $ca->getFecha_inicio():""); ?>">
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="txtFechaFin" class="col-lg-2 control-label">Fecha fin</label>
                                    <div class="col-lg-10">                                        
                                        <input type="text" class="form-control" id="txtFechaFin" name="txtFechaFin" placeholder="Fecha fin" value="<?php echo($ca != NULL ? $ca->getFecha_fin():""); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtLugar" class="col-lg-2 control-label">Lugar</label>
                                    <div class="col-lg-10">                                        
                                        <input type="text" class="form-control" id="txtLugar" name="txtLugar" placeholder="Lugar" value="<?php echo($ca != NULL ? $ca->getLugar():""); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                <label for="txtEstado">Estado:</label>
                                    <select name="txtEstado" id="txtEstado" class="form-control" placeholder="Estado">
                                        <option value="0">--------- SELECCIONE UNA OPCIÓN ---------</option>
                                        <?php
                                        //echo($ca);
                                        $sql2 = "SELECT * FROM estados where activo=1 ORDER BY nombre";
                                        $rst2 = UtilDB::ejecutaConsulta($sql2);
                                        foreach ($rst2 as $row) {
                                            echo("<option value='" . $row['cve_estado'] . "' " . ($ca != NULL ? ($ca->getCve_estado() != 0 ? ($ca->getCve_estado() == $row['cve_estado'] ? "selected" : "") : ""):"") . ">" . $row['nombre'] . "</option>");
                                        }
                                        $rst2->closeCursor();
                                        ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                <label for="txtMunicipio">Municipio:</label>
                                <select name="txtMunicipio" id="txtMunicipio" class="form-control" placeholder="Municipio" disabled>
                                        <option value="0">--------- SELECCIONE UNA OPCIÓN ---------</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="txtImagenPortada" class="col-lg-2 control-label">Imagen portada</label>
                                    <div class="col-lg-10">                                        
                                        <input type="text" class="form-control" id="txtImagenPortada" name="txtImagenPortada" placeholder="Imagen portada" value="<?php echo($ca != NULL ? $ca->getImagen_portada():""); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtPrecio" class="col-lg-2 control-label">Precio</label>
                                    <div class="col-lg-10">                                        
                                        <input type="text" class="form-control" id="txtPrecio" name="txtPrecio" placeholder="Precio" value="<?php echo($ca != NULL ? $ca->getPrecio():""); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtCupoMaximo" class="col-lg-2 control-label">Cupo máximo</label>
                                    <div class="col-lg-10">                                        
                                        <input type="text" class="form-control" id="txtCupoMaximo" name="txtCupoMaximo" placeholder="Cupo máximo" value="<?php echo($ca != NULL ? $ca->getCupo_maximo():""); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtObservaciones" class="col-lg-2 control-label">Observaciones</label>
                                    <div class="col-lg-10">                                        
                                        <input type="text" class="form-control" id="txtObservaciones" name="txtObservaciones" placeholder="Observaciones" value="<?php echo($ca != NULL ? $ca->getObservaciones():""); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10">                                        
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="cbxActivo" name="cbxActivo" <?php echo($ca != NULL ? ($ca->getCve_actividad() != 0 ? ($ca->getActivo() ? "checked" : "") : "checked"):""); ?>> Activo
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
                                <th>Actividad</th>
                                <th>Fecha inicio</th>
                                <th>Fecha fin</th>
                                <th>Lugar</th>
                                <th>Estado</th>
                                <th>Municipio</th>
                                <th>Imagen portada</th>
                                <th>Precio</th>
                                <th>Cupo máximo</th>
                                <th>Observaciones</th>
                                <th>Fecha alta</th>
                                <th>Activo</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rst as $row){?>
                            <tr>
                                <td><?php echo($row['cve_calendario']);?></td>
                                <td><?php echo($row['actividad']);?></td>
                                <td><?php echo($row['fecha_inicio']);?></td>
                                <td><?php echo($row['fecha_fin']);?></td>
                                <td><?php echo($row['lugar']);?></td>
                                <td><?php echo($row['estado']);?></td>
                                <td><?php echo($row['municipio']);?></td>
                                <td><?php echo($row['imagen_portada']);?></td>
                                <td><?php echo($row['precio']);?></td>
                                <td><?php echo($row['cupo_maximo']);?></td>
                                <td><?php echo($row['observaciones']);?></td>
                                <td><?php echo($row['fecha_alta']);?></td>
                                <td><?php echo($row['activo'] == 1 ? "Si":"No");?></td>
                                <td><a href="javascript:void(0);" onclick="editar(<?php echo($row['cve_calendario']);?>);"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                <td><a href="javascript:void(0);" onclick="if(confirm('¿Está realmente seguro de eliminar este registro?')){eliminar(<?php echo($row['cve_calendario']);?>);}else{ return false;};"><span class="glyphicon glyphicon-erase"></span></a></td>
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
            {   
                $("#txtCveCalendario").val(cve_actividad);
                $("#frm_captura").submit();
            }
            
            function eliminar(cve_actividad)
            {
                $("#xAccion").val("eliminar");
                $("#txtCveCalendario").val(cve_actividad);
                $("#frm_captura").submit();
            }

            function limpiar()
            {
                $("#xAccion").val("");
                $("#txtCveCalendario").val("0");
                $("#frm_captura").submit();
            }
        </script>
    </body>
</html>