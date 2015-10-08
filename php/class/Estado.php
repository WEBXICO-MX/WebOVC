<?php

/**
 * Estado
 *
 * @author Vasili
 */
require_once 'UtilDB.php';

class Estado {
   
    private $cve_estado;
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
    
    function __construct1($cveEstado) {
        $this->limpiar();
        $this->cve_estado = $cveEstado;
        $this->cargar();
    }
    
    private function limpiar() {
        $this->cve_estado = 0;
        $this->nombre = "";
        $this->activo = false;
        $this->_existe = false;
    }
    
     public function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $this->cve_estado = UtilDB::getSiguienteNumero("estados", "cve_estado");
            $sql = "INSERT INTO estados VALUES($this->cve_estado,'$this->nombre',$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE estados SET ";
            $sql.= "nombre = '$this->nombre',";
            $sql.= "activo=" . ($this->activo ? "1" : "0");
            $sql.= " WHERE cve_unidad_negocio = $this->cve_estado";
            $count = UtilDB::ejecutaSQL($sql);
        }
        return $count;
    }
    
    function cargar() {
        $sql = "SELECT * FROM estados WHERE cve_estado = $this->cve_estado";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cve_estado = $row['cve_estado'];
            $this->nombre = $row['nombre'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

    function borrar($cve_estado) {
      $sql = "DELETE FROM estados WHERE cve_estado = $cve_estado";
      $count = UtilDB::ejecutaSQL($sql);
      return $count;
    }
    
    function getCve_estado() {
        return $this->cve_estado;
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