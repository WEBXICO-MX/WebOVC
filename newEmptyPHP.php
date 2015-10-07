<?php

include_once 'php/class/UtilDB.php';

$sql = "INSERT INTO tipos_contenido VALUES(1,'imágenes',1),(2,'videos',1)";
$count = UtilDB::ejecutaSQL($sql);
echo("El numero de registros insertados fue de: $count");


