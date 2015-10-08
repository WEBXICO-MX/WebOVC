<?php
/**
 * Unidad de Negocio
 *
 * @author Vasili
 */
require_once 'UtilDB.php';

class UnidadNegocio {
    
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
    
    function __construct1($cveUnidadNegocio) {
        $this->limpiar();
        $this->cve_unidad_negocio = $cveUnidadNegocio;
        $this->cargar();
    }
    
    private function limpiar() {

        $this->cve_unidad_negocio = 0;
        $this->nombre = "";
        $this->activo = false;
        $this->_existe = false;
    }
    
    public function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $this->cve_unidad_negocio = UtilDB::getSiguienteNumero("unidades_negocio", "cve_unidad_negocio");
            $sql = "INSERT INTO unidades_negocio VALUES($this->cve_unidad_negocio,'$this->nombre',$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE unidades_negocio SET ";
            $sql.= "nombre = '$this->nombre',";
            $sql.= "activo=" . ($this->activo ? "1" : "0");
            $sql.= " WHERE cve_unidad_negocio = $this->cve_unidad_negocio";
            $count = UtilDB::ejecutaSQL($sql);
        }
        return $count;
    }
    
    function cargar() {
        $sql = "SELECT * FROM unidades_negocio WHERE cve_unidad_negocio = $this->cve_unidad_negocio";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cve_unidad_negocio = $row['cve_unidad_negocio'];
            $this->nombre = $row['nombre'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

    function borrar($cve_unidad_negocio) {
      $sql = "DELETE FROM unidades_negocio WHERE cve_unidad_negocio = $cve_unidad_negocio";
      $count = UtilDB::ejecutaSQL($sql);
      return $count;
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

    function setCve_unidad_negocio($cve_unidad_negocio) {
        $this->cve_unidad_negocio = $cve_unidad_negocio;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }
    
    function get_existe() {
        return $this->_existe;
    }

    function set_existe($_existe) {
        $this->_existe = $_existe;
    }
}