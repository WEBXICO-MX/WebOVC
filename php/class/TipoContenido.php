<?php
/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once 'UtilDB.php';

class TipoContenido {

    private $cve_tipo_contenido;
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

    function __construct1($cveTipoContenido) {
        $this->limpiar();
        $this->cve_tipo_contenido = $cveTipoContenido;
        $this->cargar();
    }

    private function limpiar() {

        $this->cve_tipo_contenido = 0;
        $this->nombre = "";      
        $this->activo = false;
        $this->_existe = false;
    }

    public function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $this->cve_tipo_contenido = UtilDB::getSiguienteNumero("tipos_contenido", "cve_tipo_contenido");
            $sql = "INSERT INTO tipos_contenido VALUES($this->cve_tipo_contenido,'$this->nombre',$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE tipos_contenido SET ";
            $sql.= "nombre = '$this->nombre',";           
            $sql.= "activo=" . ($this->activo ? "1" : "0");
            $sql.= " WHERE cve_tipo_contenido = $this->cve_tipo_contenido";
            $count = UtilDB::ejecutaSQL($sql);
        }
        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM tipos_contenido WHERE cve_tipo_contenido = $this->cve_tipo_contenido";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cve_tipo_contenido = $row['cve_tipo_contenido'];
            $this->nombre = $row['nombre'];           
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

    function borrar() {
      $sql = "DELETE FROM tipos_contenido WHERE cve_tipo_contenido = $this->cve_tipo_contenido";
      $count = UtilDB::ejecutaSQL($sql);
      return $count;
    }

    function getCve_tipo_contenido() {
        return $this->cve_tipo_contenido;
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

    function setCve_tipo_contenido($cve_tipo_contenido) {
        $this->cve_tipo_contenido = $cve_tipo_contenido;
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