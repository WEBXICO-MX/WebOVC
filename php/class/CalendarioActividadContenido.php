<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once 'UtilDB.php';
require_once 'CalendarioActividad.php';
require_once 'TipoContenido.php';

class CalendarioActividadContenido {

    private $cve_actividad_contenido;
     /**
    * @var CalendarioActividad $cve_calendario Calendario CalendarioActividad
    */
    private $cve_calendario;
     /**
    * @var TipoContenido $cve_tipo_contenido Tipo TipoContenido
    */
    private $cve_tipo_contenido;
    private $url;
    private $activo;
    private $_existe;

    function __construct() {
        $this->limpiar();

        $args = func_get_args();
        $nargs = func_num_args();

        switch ($nargs) {
            case 2:
                self::__construct1($args[0],$args[1]);
                break;
            //case 2:
            //self::__construct2($args[0], $args[1]);
            //break;
        }
    }

    function __construct1($cveActividadContenido) {
        $this->limpiar();
        
        $this->cve_actividad_contenido = $cveActividadContenido;      
        $this->cargar();
    }

    private function limpiar() {
        $this->cve_actividad_contenido = 0;
        $this->cve_calendario = NULL;
        $this->cve_tipo_contenido = NULL;
        $this->url = "";
        $this->activo = false;
        $this->_existe = false;
    }

    public function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $sql = "INSERT INTO calendario_actividades_contenido VALUES( ";
            $sql.= "$this->cve_actividad_contenido,";
            $sql.= $this->cve_calendario->getCve_calendario().","; 
            $sql.= $this->cve_tipo_contenido->getCve_tipo_contenido().",'$this->url',$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) { 
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE calendario_actividades_contenido SET ";
            $sql.= "cve_actividad_contenido = $this->cve_actividad_contenido,";
            $sql.= "cve_calendario =". $this->cve_calendario->getCve_calendario().",";
            $sql.= "cve_tipo_contenido =". $this->cve_tipo_contenido->getCve_tipo_contenido().",";
            $sql.= "url = '$this->url',";
            $sql.= "activo=" . ($this->activo ? "1" : "0");
            $sql.= " WHERE cve_actividad_contenido = $this->cve_actividad_contenido";
            $count = UtilDB::ejecutaSQL($sql);
        }
        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM calendario_actividades_contenido WHERE cve_actividad_contenido = $this->cve_actividad_contenido";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cve_actividad_contenido = $row['cve_actividad_contenido'];
            $this->cve_calendario = new CalendarioActividad($row['cve_calendario']);
            $this->cve_tipo_contenido = new TipoContenido($row['cve_tipo_contenido']);
            $this->url = $row['url'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

    function borrar() {
        $sql = "DELETE FROM calendario_actividades_contenido WHERE cve_actividad_contenido= $this->cve_actividad_contenido";
        $count = UtilDB::ejecutaSQL($sql);
        return $count;
    }

    function getCve_actividad_contenido() {
        return $this->cve_actividad_contenido;
    }
    
    /**
    * @return CalendarioActividad Devuelve tipo CalendarioActividad
    */
    function getCve_calendario() {
        return $this->cve_calendario;
    }

     /**
    * @return TipoContenido Devuelve tipo TipoContenido
    */
    function getCve_tipo_contenido() {
        return $this->cve_tipo_contenido;
    }

    function getUrl() {
        return $this->url;
    }

    function getActivo() {
        return $this->activo;
    }

    function get_existe() {
        return $this->_existe;
    }

    function setCve_actividad_contenido($cve_actividad_contenido) {
        $this->cve_actividad_contenido = $cve_actividad_contenido;
    }
    
    /**
    * Establece el calendario.
    *
    * @param CalendarioActividad $cve_calendario Objeto tipo CalendarioActividad
    *
    */
    
    function setCve_calendario($cve_calendario) {
        $this->cve_calendario = $cve_calendario;
    }

    /**
    * Establece el tipo de contenido.
    *
    * @param TipoContenido $cve_tipo_contenido Objeto tipo TipoContenido
    *
    */
    function setCve_tipo_contenido($cve_tipo_contenido) {
        $this->cve_tipo_contenido = $cve_tipo_contenido;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function set_existe($_existe) {
        $this->_existe = $_existe;
    }
}
