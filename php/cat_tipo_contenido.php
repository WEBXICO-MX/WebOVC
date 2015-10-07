<?php
require_once '../class/TipoContenido.php';
require_once '../class/Utilerias.php';
//session_start();

/* if (!isset($_SESSION['cve_usuario'])) 
  {
  header('Location:login.php');
  return;
  } */

$tc = new TipoContenido();
$count = 0;$xAccion = "0";$txtCveTipoContenido = 0;$txtNombre = "";$cbxActivo = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $xAccion = test_input($_POST["xAccion"]);
    $txtCveTipoContenido = (int)test_input($_POST["txtCveTipoContenido"]);
    $txtNombre = test_input($_POST["txtNombre"]);
    $cbxActivo = test_input($_POST["cbxActivo"]);

    if ($txtCveTipoContenido != 0) {
            $tc = new TipoContenido($txtCveTipoContenido);
        }

    if ($xAccion == 'grabar') {
            $tc->setNombre($txtNombre);
            $tc->setActivo($cbxActivo != NULL ? "1" : "0");
            $count = $tc->grabar();
        }
        elseif ($xAccion == 'eliminar') {
            $rito->borrar($_POST['txtIdRitoEli']);
        }
        elseif ($xAccion == 'logout') {
            /*unset($_SESSION['cve_usuario']);
            header('Location:login.php');
            return;*/
        }
}

$sql = "SELECT * FROM tipos_contenido ORDER BY cve_tipo_contenido";
$rst = UtilDB::ejecutaConsulta($sql);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Tipos de contenido | Admin</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../twbs/bootswatch/themes/flatly/bootstrap.min.css" rel="stylesheet"/>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1 id="forms">Tipos de contenido</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="well bs-component">
                        <form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <fieldset>
                                <legend>Captura</legend>
                                <div class="form-group">
                                    <label for="txtCveTipoContenido" class="col-lg-2 control-label">ID Tipo contenido</label>
                                    <div class="col-lg-10">
                                        <input type="hidden" id="xAccion" name="xAccion" value="0">
                                        <input type="text" class="form-control" id="txtCveTipoContenido" name="txtCveTipoContenido" placeholder="ID Tipo contenido">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtNombre" class="col-lg-2 control-label">Nombre</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="txtNombre" nombre="txtNombre" placeholder="Nombre">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="cbxActivo" name="cbxActivo"> Activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <button type="reset" class="btn btn-default">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <div id="source-button" class="btn btn-primary btn-xs" style="display: none;">&lt; &gt;</div></div>
                </div>
            </div>
        </div>
        <script src="../js/jQuery/jquery-1.11.3.min.js"></script>
        <script src="../twbs/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    </body>
</html>
