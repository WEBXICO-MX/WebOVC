<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once 'UtilDB.php';

class TipoActividad {

    private $cve_tipo;
    private $cve_unidad_negocio;
    private $nombre;
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

    function __construct1($cveTipo) {
        $this->limpiar();
        $this->cve_tipo = $cveTipo;
        $this->cargar();
    }

    private function limpiar() {
        $this->cve_tipo = 0;
        $this->cve_unidad_negocio = 0;
        $this->nombre = "";
        $this->activo = false;
        $this->_existe = false;
    }

    public function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $this->cve_tipo = UtilDB::getSiguienteNumero("tipos_actividades", "cve_tipo");
            $sql = "INSERT INTO tipos_actividades VALUES($this->cve_tipo,$this->cve_unidad_negocio,'$this->nombre',$this->activo)";
            echo($sql);
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE tipos_actividades SET ";
            $sql.= "cve_unidad_negocio = $this->cve_unidad_negocio,";
            $sql.= "nombre = '$this->nombre',";
            $sql.= "activo=" . ($this->activo ? "1" : "0");
            $sql.= " WHERE cve_tipo = $this->cve_tipo";
            echo($sql);
            $count = UtilDB::ejecutaSQL($sql);
        }
        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM tipos_actividades WHERE cve_tipo = $this->cve_tipo";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cve_tipo = $row['cve_tipo'];
            $this->cve_unidad_negocio = $row['cve_unidad_negocio'];
            $this->nombre = $row['nombre'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

    function borrar() {
        $sql = "DELETE FROM tipos_actividades WHERE cve_tipo = $this->cve_tipo";
        $count = UtilDB::ejecutaSQL($sql);
        return $count;
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
    
    function getActivo() {
        return $this->activo;
    }

    function get_existe() {
        return $this->_existe;
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
    
    function setActivo($activo) {
        $this->activo = $activo;
    }

    function set_existe($_existe) {
        $this->_existe = $_existe;
    }
}