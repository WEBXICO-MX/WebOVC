<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once 'UtilDB.php';

class Municipio {

    private $cve_estado;
    private $cve_municipio;
    private $nombre;
    private $activo;
    private $_existe;

    function __construct() {
        $this->limpiar();

        $args = func_get_args();
        $nargs = func_num_args();

        switch ($nargs) {
            case 2:
                self::__construct1($args[0], $args[1]);
                break;
        }
    }

    function __construct1($cveEstado, $cveMunicipio) {
        $this->limpiar();
        $this->cve_estado = $cveEstado;
        $this->cve_municipio = $cveMunicipio;
        $this->cargar();
    }

    private function limpiar() {

        $this->cve_estado = 0;
        $this->cve_municipio = 0;
        $this->nombre = "";
        $this->activo = false;
        $this->_existe = false;
    }

    public function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $this->cve_municipio = UtilDB::getSiguienteNumero("municipios", "cve_municipioo");
            $sql = "INSERT INTO municipios VALUES($this->cve_estado,$this->cve_municipio,'$this->nombre',$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE municipios SET ";
            $sql.= "nombre = '$this->nombre',";
            $sql.= "cve_estado = '$this->cve_estado',";
            $sql.= "activo=" . ($this->activo ? "1" : "0");
            $sql.= " WHERE cve_estado = $this->cve_estado AND cve_municipio = $this->cve_municipio";
            $count = UtilDB::ejecutaSQL($sql);
        }
        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM municipios WHERE cve_estado = $this->cve_estado AND cve_municipio = $this->cve_municipio";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cve_estado = $row['cve_estado'];
            $this->cve_estado = $row['cve_municipio'];
            $this->nombre = $row['nombre'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

    function borrar() {
        $sql = "DELETE FROM municipios WHERE cve_estado = $this->cve_estado AND cve_municipio = $this->cve_municipio";
        $count = UtilDB::ejecutaSQL($sql);
        return $count;
    }

    function getCve_estado() {
        return $this->cve_estado;
    }

    function getCve_municipio() {
        return $this->cve_municipio;
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

    function setCve_estado($cve_estado) {
        $this->cve_estado = $cve_estado;
    }

    function setCve_municipio($cve_municipio) {
        $this->cve_municipio = $cve_municipio;
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