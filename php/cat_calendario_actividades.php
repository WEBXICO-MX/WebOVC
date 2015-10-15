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
    
    $fi = strtotime(str_replace('/', '-', (test_input($_POST["txtFechaInicio"]) . " " . "00:00:00")));
    $ff = strtotime(str_replace('/', '-', (test_input($_POST["txtFechaFin"]) . " " . "23:59:59")));
    $txtFechaInicio = date('Y-m-d H:i:s', $fi);
    $txtFechaFin = date('Y-m-d H:i:s', $ff);

    $txtLugar = test_input($_POST["txtLugar"]);
    $txtEstado = test_input($_POST["txtEstado"]);
    $txtMunicipio = isset($_POST["txtMunicipio"]) ? test_input($_POST["txtMunicipio"]):"";
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

/*$sql = "SELECT ca.cve_calendario,a.nombre AS actividad,ca.fecha_inicio,ca.fecha_fin,ca.lugar,e.nombre AS estado,m.nombre AS municipio,ca.imagen_portada,ca.precio,ca.cupo_maximo,ca.observaciones,ca.fecha_alta,ca.activo ";
$sql .= "FROM calendario_actividades AS ca ";
$sql .= "INNER JOIN actividades AS a ON a.cve_actividad = ca.cve_actividad ";
$sql .= "INNER JOIN estados AS e ON e.cve_estado = ca.cve_estado ";
$sql .= "INNER JOIN municipios AS m ON m.cve_municipio = ca.cve_municipio AND m.cve_estado = ca.cve_estado";*/

$sql = "SELECT ca.cve_calendario, a.nombre AS actividad, ca.imagen_portada ,ca.activo FROM calendario_actividades AS ca INNER JOIN actividades AS a ON a.cve_actividad = ca.cve_actividad ORDER BY ca.fecha_inicio DESC";
$rst = UtilDB::ejecutaConsulta($sql);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Calendario actividades | Admin</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../js/jquery-ui-1.11.4/jquery-ui.min.css" rel="stylesheet"/>
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
                                    <label for="txtCveCalendario" class="col-lg-4 control-label">ID</label>
                                    <div class="col-lg-8">
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
                                    <div class="date-form">
                                        <div class="form-horizontal">
                                            <div class="control-group">
                                                <label for="txtFechaFin">Fecha inicio</label>
                                                <div class="controls">
                                                    <div class="input-group">
                                                        <input id="txtFechaInicio" name="txtFechaInicio" type="text" class="date-picker form-control"  value="<?php echo(substr(str_replace('-', '/', $ca->getFecha_inicio()), 0, 10)); ?>"/>
                                                        <label for="txtFechaInicio" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                                <div class="form-group">
                                    <div class="date-form">
                                        <div class="form-horizontal">
                                            <div class="control-group">
                                                <label for="txtFechaFin">Fecha fin</label>
                                                <div class="controls">
                                                    <div class="input-group">
                                                        <input id="txtFechaFin" name="txtFechaFin" type="text" class="date-picker form-control"  value="<?php echo(substr(str_replace('-', '/', $ca->getFecha_fin()), 0, 10)); ?>"/>
                                                        <label for="txtFechaFin" class="input-group-addon btn"><span class="glyphicon glyphicon-calendar"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                        <input type="text" class="form-control" id="txtImagenPortada" name="txtImagenPortada" placeholder="Imagen portada" value="<?php echo($ca != NULL ? $ca->getImagen_portada():""); ?>" readonly>
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
                                    <label for="txtObservaciones" class="col-lg-4 control-label">Observaciones</label>
                                    <div class="col-lg-8">                                        
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
            <div class="row">
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
                                <th>Imagen portada</th>
                                <th>Activo</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rst as $row){?>
                            <tr>
                                <td><?php echo($row['cve_calendario']);?></td>
                                <td><a href="javascript:void(0);" data-toggle="modal" data-remote="cat_calendario_actividades_id.php?id=<?php echo($row['cve_calendario']); ?>" data-target="#myModal"><?php echo($row['actividad']);?></a></td>
                                <th><?php echo($row['imagen_portada'] != "" ? "<img src=\"../img/File-JPG-icon.png\" alt=\"" . utf8_encode($row['actividad']) . "\" title=\"" . $row['actividad'] . "\" data-toggle=\"popover\" data-content=\"<img src='../" . $row['imagen_portada'] . "' alt='" . $row['actividad'] . "' class='img-responsive'/>\" style=\"cursor:pointer;\"/><br/><br/><a data-toggle=\"modal\" data-target=\"#myModal\" data-remote=\"cat_calendario_actividades_img.php?xCveCalendario=" . $row['cve_calendario'] ."\" href=\"javascript:void(0);\">Cambiar imagen</a>" : "<a data-toggle=\"modal\" data-target=\"#myModal\" data-remote=\"cat_calendario_actividades_img.php?xCveCalendario=" . $row['cve_calendario'] . "\" href=\"javascript:void(0);\">Subir imagen</a>"); ?></th>
                                <td><?php echo($row['activo'] == 1 ? "Si":"No");?></td>
                                <td><a href="javascript:void(0);" onclick="editar(<?php echo($row['cve_calendario']);?>);"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                <td><a href="javascript:void(0);" onclick="if(confirm('¿Está realmente seguro de eliminar este registro?')){eliminar(<?php echo($row['cve_calendario']);?>);}else{ return false;};"><span class="glyphicon glyphicon-erase"></span></a></td>
                            </tr>
                            <?php } $rst->closeCursor();?>
                        </tbody>
                    </table> 
                </div>
            </div>
            </div>
            <?php }?>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" ria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../js/jQuery/jquery-1.11.3.min.js"></script>
        <script src="../js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
        <script src="../js/jquery-ui-1.11.4/jquery.ui.datepicker-es-MX.js"></script>
        <script src="../twbs/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
        <script>
            
            $(document).ready(function () {

                $(".date-picker").datepicker({yearRange: "-0:+10", changeMonth: true, changeYear: true});
                $.datepicker.setDefaults($.datepicker.regional[ "es-MX" ]);
                
                $('[data-toggle="popover"]').popover({placement: 'top', html: true, trigger: 'click hover'});

                $('body').on('hidden.bs.modal', '.modal', function () {
                    $(this).removeData('bs.modal');
                });
                
                $("#txtEstado").change(function(){
                    $("#txtMunicipio").prop("disabled",true);
                    $("#txtMunicipio").html("");
                    var op = this.value;
                    $("#txtMunicipio").load("cat_calendario_actividades_ajax.php",{"xAccion":"getMunicipios","xCveEstado":op},function(){ $("#txtMunicipio").prop("disabled",false); });
                });

            });
            
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
            
            function subir()
            {
                if ($("#fileToUpload").val() !== "")
                {
                    $("#xAccion2").val("upload");
                    $("#frmUpload").submit();
                }
                else
                {
                    alert("No ha seleccionado un archivo para subir.");
                }
            }
        </script>
    </body>
</html>