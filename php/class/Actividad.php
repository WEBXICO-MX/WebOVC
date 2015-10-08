<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once 'UtilDB.php';

class Actividad {

    private $cve_actividad;
    private $cve_tipo;
    private $cve_unidad_negocio;
    private $nombre;
    private $descripcion;
    private $activo;
    private $_existe;

    function __construct() {
        $this->limpiar();

        $args = func_get_args();
        $nargs = func_num_args();

        switch ($nargs) {
            case 3:
                self::__construct1($args[0], $args[1], $args[2]);
                break;
            //case 2:
            //self::__construct2($args[0], $args[1]);
            //break;
        }
    }

    function __construct1($cveActividad, $cveTipo, $cveUnidadNegocio) {
        $this->limpiar();
        $this->cve_actividad = $cveActividad;
        $this->cve_tipo = $cveTipo;
        $this->cve_unidad_negocio = $cveUnidadNegocio;
        $this->cargar();
    }

    private function limpiar() {
        $this->cve_actividad = 0;
        $this->cve_tipo = "";
        $this->cve_unidad_negocio = "";
        $this->nombre = "";
        $this->descripcion = "";
        $this->activo = false;
        $this->_existe = false;
    }

    public function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $this->cve_actividad = UtilDB::getSiguienteNumero("actividades", "cve_actividad");
            $sql = "INSERT INTO actividades VALUES($this->cve_actividad,$this->cve_tipo,$this->cve_unidad_negocio,'$this->nombre','$this->descripcion',$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE actividades SET ";
            $sql.= "cve_tipo = '$this->cve_tipo',";
            $sql.= "cve_unidad_negocio = '$this->cve_unidad_negocio',";
            $sql.= "nombre = '$this->nombre',";
            $sql.= "descripcion = '$this->descripcion',";
            $sql.= "activo=" . ($this->activo ? "1" : "0");
            $sql.= " WHERE cve_actividad = $this->cve_actividad AND cve_tipo = $this->cve_tipo AND cve_unidad_negocio = $this->cve_unidad_negocio";
            $count = UtilDB::ejecutaSQL($sql);
        }
        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM actividades WHERE cve_actividad = $this->cve_actividad AND cve_tipo = $this->cve_tipo AND cve_unidad_negocio = $this->cve_unidad_negocio";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cve_actividad = $row['cve_actividad'];
            $this->cve_tipo = $row['cve_tipo'];
            $this->cve_unidad_negocio = $row['cve_unidad_negocio'];
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

    function borrar($cveActividad, $cveTipo, $cveUnidadNegocio) {
        $sql = "DELETE FROM actividades WHERE cve_actividad = $cveActividad AND cve_tipo = $cveTipo AND cve_unidad_negocio = $cveUnidadNegocio";
        $count = UtilDB::ejecutaSQL($sql);
        return $count;
    }

    function getCve_actividad() {
        return $this->cve_actividad;
    }

    function getCve_tipo() {
        return $this->cve_tipo;
    }

    function getCve_unidad_negocio() {
        return $this->cve_unidad_negocio;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getActivo() {
        return $this->activo;
    }

    function get_existe() {
        return $this->_existe;
    }

    function setCve_actividad($cve_actividad) {
        $this->cve_actividad = $cve_actividad;
    }

    function setCve_tipo($cve_tipo) {
        $this->cve_tipo = $cve_tipo;
    }

    function setCve_unidad_negocio($cve_unidad_negocio) {
        $this->cve_unidad_negocio = $cve_unidad_negocio;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function set_existe($_existe) {
        $this->_existe = $_existe;
    }

}