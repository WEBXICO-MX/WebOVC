<?php

require_once 'class/UtilDB.php';
require_once 'lib/Utilerias.php';

$cve_estado = isset($_POST["xCveEstado"]) ? (int)test_input($_POST["xCveEstado"]):0;
$xAccion = test_input($_POST["xAccion"]);
$tmp = "";

if ($xAccion != "") {
    if ($xAccion == "getMunicipios") {
        $sql = "SELECT * FROM municipios WHERE cve_estado = $cve_estado ORDER BY nombre";
        $rst = UtilDB::ejecutaConsulta($sql);
        if ($rst->rowCount() != 0) {
            foreach ($rst as $row) {
                $tmp .= "<option value=\"".$row['cve_municipio']."\">".$row['nombre']."</option>";
            }
        }
        $rst->closeCursor();
        echo($tmp);
        $tmp = "";
        return;
    }
}