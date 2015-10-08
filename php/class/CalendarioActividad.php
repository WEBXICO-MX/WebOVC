<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once 'UtilDB.php';

class CalendarioActividad {

    private $cve_calendario;
    private $cve_actividad;
    private $cve_tipo;
    private $cve_unidad_negocio;
    private $fecha_inicio;
    private $fecha_fin;
    private $lugar;
    private $cve_estado;
    private $cve_municipio;
    private $imagen_portada;
    private $precio;
    private $cupo_maximo;
    private $observaciones;
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

    function __construct1($cveCalendario) {
        $this->limpiar();
        $this->cve_calendario = $cveCalendario;
        $this->cargar();
    }

    private function limpiar() {

        $this->cve_calendario = 0;
        $this->cve_actividad = 0;
        $this->cve_tipo = 0;
        $this->cve_unidad_negocio = 0;
        $this->fecha_inicio = NULL;
        $this->fecha_fin = NULL;
        $this->lugar = "";
        $this->cve_estado = 0;
        $this->cve_municipio = 0;
        $this->imagen_portada = "";
        $this->precio = 0.0;
        $this->cupo_maximo = 0;
        $this->observaciones = "";
        $this->fecha_alta = NULL;
        $this->activo = false;
        $this->_existe = false;
    }

    public function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $this->cve_calendario = UtilDB::getSiguienteNumero("calendario_actividades", "cve_calendario");
            $sql = "INSERT INTO calendario_actividades VALUES($this->cve_calendario,$this->cve_actividad,$this->cve_tipo,$this->cve_unidad_negocio,'$this->fecha_inicio','$this->fecha_fin','$this->lugar',$this->cve_estado,$this->cve_municipio,'$this->imagen_portada',$this->precio,$this->cupo_maximo,'$this->observaciones',NOW(),$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE calendario_actividades SET ";
            $sql.= "cve_actividad = $this->cve_actividad,";
            $sql.= "cve_tipo = $this->cve_tipo,";
            $sql.= "cve_unidad_negocio = $this->cve_unidad_negocio,";
            $sql.= "fecha_inicio = '$this->fecha_inicio',";
            $sql.= "fecha_fin = '$this->fecha_fin',";
            $sql.= "lugar = '$this->lugar',";
            $sql.= "cve_estado = $this->cve_estado,";
            $sql.= "cve_municipio = $this->cve_municipio,";
            $sql.= "imagen_portada = '$this->imagen_portada',";
            $sql.= "precio = $this->precio,";
            $sql.= "cupo_maximo = $this->cupo_maximo,";
            $sql.= "observaciones = '$this->observaciones',";
            $sql.= "activo=" . ($this->activo ? "1" : "0");
            $sql.= " WHERE cve_calendario = $this->cve_calendario";
            $count = UtilDB::ejecutaSQL($sql);
        }
        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM calendario_actividades WHERE cve_calendario = $this->cve_calendario";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cve_calendario = $row['cve_calendario'];
            $this->cve_actividad = $row['cve_actividad'];
            $this->cve_tipo = $row['cve_tipo'];
            $this->cve_unidad_negocio = $row['cve_unidad_negocio'];
            $this->fecha_inicio = $row['fecha_inicio'];
            $this->fecha_fin = $row['fecha_fin'];
            $this->lugar = $row['lugar'];
            $this->cve_estado = $row['cve_estado'];
            $this->cve_municipio = $row['cve_municipio'];
            $this->imagen_portada = $row['imagen_portada'];
            $this->precio = $row['precio'];
            $this->cupo_maximo = $row['cupo_maximo'];
            $this->observaciones = $row['observaciones'];
            $this->fecha_alta = $row['fecha_alta'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

    function borrar($cveCalendario) {
        $sql = "DELETE FROM calendario_actividades WHERE cve_unidad_negocio = $cveCalendario";
        $count = UtilDB::ejecutaSQL($sql);
        return $count;
    }

    function getCve_calendario() {
        return $this->cve_calendario;
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

    function getFecha_inicio() {
        return $this->fecha_inicio;
    }

    function getFecha_fin() {
        return $this->fecha_fin;
    }

    function getLugar() {
        return $this->lugar;
    }

    function getCve_estado() {
        return $this->cve_estado;
    }

    function getCve_municipio() {
        return $this->cve_municipio;
    }

    function getImagen_portada() {
        return $this->imagen_portada;
    }

    function getPrecio() {
        return $this->precio;
    }

    function getCupo_maximo() {
        return $this->cupo_maximo;
    }

    function getObservaciones() {
        return $this->observaciones;
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

    function setCve_calendario($cve_calendario) {
        $this->cve_calendario = $cve_calendario;
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

    function setFecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    function setFecha_fin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }

    function setLugar($lugar) {
        $this->lugar = $lugar;
    }

    function setCve_estado($cve_estado) {
        $this->cve_estado = $cve_estado;
    }

    function setCve_municipio($cve_municipio) {
        $this->cve_municipio = $cve_municipio;
    }

    function setImagen_portada($imagen_portada) {
        $this->imagen_portada = $imagen_portada;
    }

    function setPrecio($precio) {
        $this->precio = $precio;
    }

    function setCupo_maximo($cupo_maximo) {
        $this->cupo_maximo = $cupo_maximo;
    }

    function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;
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