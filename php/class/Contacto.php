<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once 'UtilDB.php';

class Contacto {

    private $cve_contacto;
    private $nombre;
    private $correo;
    private $telefono;
    private $comentario;
    private $fecha_alta;
    private $activo;
    private $_existe;

    function __construct() {
        $this->limpiar();

        $args = func_get_args();
        $nargs = func_num_args();

        switch ($nargs) {
            case 1:
                self::__construct1($args[0]);
                break;
            //case 2:
            //self::__construct2($args[0], $args[1]);
            //break;
        }
    }

    function __construct1($cveContacto) {
        $this->limpiar();
        $this->cve_contacto = $cveContacto;
        $this->cargar();
    }

    private function limpiar() {
        $this->cve_contacto = 0;
        $this->nombre = "";
        $this->correo = "";
        $this->telefono = "";
        $this->comentario = "";
        $this->fecha_alta = NULL;
        $this->activo = false;
        $this->_existe = false;
    }

    public function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $this->cve_contacto = UtilDB::getSiguienteNumero("contactos", "cve_contacto");
            $sql = "INSERT INTO contactos VALUES($this->cve_contacto,'$this->nombre','$this->correo','$this->telefono','$this->comentario',NOW(),$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE contactos SET ";
            $sql.= "nombre = '$this->nombre',";
            $sql.= "correo = '$this->correo',";
            $sql.= "telefono = '$this->telefono',";
            $sql.= "comentario = '$this->comentario',";
            $sql.= "activo=" . ($this->activo ? "1" : "0");
            $sql.= " WHERE cve_contacto = $this->cve_contacto";
            $count = UtilDB::ejecutaSQL($sql);
        }
        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM contactos WHERE cve_contacto = $this->cve_contacto";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cve_contacto = $row['cve_contacto'];
            $this->nombre = $row['nombre'];
            $this->correo = $row['correo'];
            $this->telefono = $row['telefono'];
            $this->comentario = $row['comentario'];
            $this->fecha_alta = $row['fecha_alta'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

    function borrar() {
        $sql = "DELETE FROM contactos WHERE cve_contacto = $this->cve_contacto";
        $count = UtilDB::ejecutaSQL($sql);
        return $count;
    }

    function getCve_contacto() {
        return $this->cve_contacto;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getCorreo() {
        return $this->correo;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getComentario() {
        return $this->comentario;
    }

    function getFecha_alta() {
        return $this->fecha_alta;
    }

    function getActivo() {
        return $this->activo;
    }

    function get_existe() {
        return $this->_existe;
    }

    function setCve_contacto($cve_contacto) {
        $this->cve_contacto = $cve_contacto;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setCorreo($correo) {
        $this->correo = $correo;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    function setFecha_alta($fecha_alta) {
        $this->fecha_alta = $fecha_alta;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function set_existe($_existe) {
        $this->_existe = $_existe;
    }

}