<?php

require_once 'class/Contacto.php';
require_once 'lib/Utilerias.php';

$contacto = new Contacto();
$count = 0;
$xAccion = "";
$txtNombre = "";
$txtEmail = "";
$txtTel = "";
$txtComentario = "";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $xAccion = test_input($_POST["xAccion"]);
    $txtNombre = test_input($_POST["txtNombre"]);
    $txtEmail = test_input($_POST["txtEmail"]);
    $txtTel = test_input($_POST["txtTel"]);
    $txtComentario = test_input($_POST["txtComentario"]);
    echo($xAccion);
    if ($xAccion == 'guardar_contacto') {
        $contacto->setNombre($txtNombre);
        $contacto->setCorreo($txtEmail);
        $contacto->setTelefono($txtTel);
        $contacto->setComentario($txtComentario);
        $count = $contacto->grabar();

        header('Location:../index.php');
        return;
    }
}