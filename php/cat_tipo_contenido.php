<?php
require_once 'class/TipoContenido.php';
require_once 'lib/Utilerias.php';
//session_start();

/* if (!isset($_SESSION['cve_usuario'])) 
  {
  header('Location:login.php');
  return;
  } */

$tc = new TipoContenido();
$count = 0;
$xAccion = "";
$txtCveTipoContenido = 0;
$txtNombre = "";
$cbxActivo = 0;
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $xAccion = test_input($_POST["xAccion"]);
    $txtCveTipoContenido = (int) test_input($_POST["txtCveTipoContenido"]);
    $txtNombre = test_input($_POST["txtNombre"]);
    $cbxActivo = isset($_POST["cbxActivo"]) ? 1 : 0;

    if ($txtCveTipoContenido != 0) {
        $tc = new TipoContenido($txtCveTipoContenido);
    }

    if ($xAccion == 'grabar') {
        $tc->setNombre($txtNombre);
        $tc->setActivo($cbxActivo);
        $count = $tc->grabar();

        if ($count > 0) {
            $msg = "Los datos han sido guardados.";
        } else {
            $msg = "Ha ocurrido un imprevisto al guardar los datos";
        }
    } elseif ($xAccion == 'eliminar') {
        $count = $tc->borrar();
        if($count > 0)
        { $msg = "El registro ha sido borrado con éxito"; $tc = NULL;}
        else
        { $msg = "Ha ocurrido un imprevisto al borrar el registro"; $tc = NULL;}
    } elseif ($xAccion == 'logout') {
        /* unset($_SESSION['cve_usuario']);
          header('Location:login.php');
          return; */
    }
}

$sql = "SELECT * FROM tipos_contenido ORDER BY cve_tipo_contenido";
$rst = UtilDB::ejecutaConsulta($sql);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Grupo HISA | Tipos de contenido</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="../img/favicon.png"/>
        <link href="../twbs/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="page-header">
                        <h1 id="forms">Tipos de contenido</h1>
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
                                    <label for="txtCveTipoContenido" class="col-lg-2 control-label">ID Tipo contenido</label>
                                    <div class="col-lg-10">
                                        <input type="hidden" id="xAccion" name="xAccion" value="0">
                                        <input type="text" class="form-control" id="txtCveTipoContenido" name="txtCveTipoContenido" placeholder="ID Tipo contenido" readonly value="<?php echo($tc != NULL ? $tc->getCve_tipo_contenido():""); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtNombre" class="col-lg-2 control-label">Nombre</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="txtNombre" name="txtNombre" placeholder="Nombre" value="<?php echo($tc != NULL ? $tc->getNombre():""); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtIcono" class="col-lg-2 control-label">Icono</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="txtIcono" name="txtIcono" placeholder="Icono" value="<?php echo($tc != NULL ? $tc->getIcono():""); ?>" readonly>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="cbxActivo" name="cbxActivo" <?php echo($tc != NULL ? ($tc->getCve_tipo_contenido() != 0 ? ($tc->getActivo() ? "checked" : "") : "checked"):""); ?>> Activo
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
                                <th>Nombre</th>
                                <th>Icono</th>
                                <th>Activo</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rst as $row){?>
                            <tr>
                                <td><?php echo($row['cve_tipo_contenido']);?></td>
                                <td><?php echo($row['nombre']);?></td>
                                <th><?php echo($row['icono'] != "" ? "<img src=\"../img/File-JPG-icon.png\" alt=\"" . utf8_encode($row['nombre']) . "\" title=\"" . $row['nombre'] . "\" data-toggle=\"popover\" data-content=\"<img src='../" . $row['icono'] . "' alt='" . $row['nombre'] . "' class='img-responsive'/>\" style=\"cursor:pointer;\"/><br/><br/><a data-toggle=\"modal\" data-target=\"#myModal\" data-remote=\"cat_tipo_contenido_img.php?xCveTipoContenido=" . $row['cve_tipo_contenido'] ."\" href=\"javascript:void(0);\">Cambiar imagen</a>" : "<a data-toggle=\"modal\" data-target=\"#myModal\" data-remote=\"cat_tipo_contenido_img.php?xCveTipoContenido=" . $row['cve_tipo_contenido'] . "\" href=\"javascript:void(0);\">Subir imagen</a>"); ?></th>
                                <td><?php echo($row['activo'] == 1 ? "Si":"No");?></td>
                                <td><a href="javascript:void(0);" onclick="editar(<?php echo($row['cve_tipo_contenido']);?>);"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                <td><a href="javascript:void(0);" onclick="if(confirm('¿Está realmente seguro de eliminar este registro?')){eliminar(<?php echo($row['cve_tipo_contenido']);?>);}else{ return false;};"><span class="glyphicon glyphicon-erase"></span></a></td>
                            </tr>
                            <?php } $rst->closeCursor();?>
                        </tbody>
                    </table> 
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
        <script src="../twbs/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
        <script>
            
            $(document).ready(function(){
                
                $('[data-toggle="popover"]').popover({placement: 'top', html: true, trigger: 'click hover'});

                $('body').on('hidden.bs.modal', '.modal', function () {
                    $(this).removeData('bs.modal');
                });
                
            });
            
            function grabar()
            {
                $("#xAccion").val("grabar");
                $("#frm_captura").submit();
            }
            
            function editar(cve_tipo_contenido)
            {   $("#txtCveTipoContenido").val(cve_tipo_contenido);
                $("#frm_captura").submit();
            }
            
            function eliminar(cve_tipo_contenido)
            {
                $("#xAccion").val("eliminar");
                $("#txtCveTipoContenido").val(cve_tipo_contenido);
                $("#frm_captura").submit();
            }

            function limpiar()
            {
                $("#xAccion").val("");
                $("#txtCveTipoContenido").val("0");
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