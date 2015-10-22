<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
class UtilDB {

    private static $servername = "mysql.hostinger.es"; //localhost
    private static $username = "u798069583_ovc"; //root
    private static $password = "9811977";
    private static $database = "u798069583_ovc"; //ovc
    private static $cnx = NULL;

    function __construct() {
        
    }

    static function getConnection() {
        try {
            $cnxString = "mysql:host=" . UtilDB::$servername . ";dbname=" . UtilDB::$database;
            $params = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_CASE => PDO::CASE_LOWER);
            $cnx = new PDO($cnxString, UtilDB::$username, UtilDB::$password, $params);
            //$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //$cnx->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $cnx;
    }

    static function getSiguienteNumero($tabla, $campo) {
        $cnx = UtilDB::getConnection();
        $sql = "SELECT MAX($campo) AS num FROM $tabla";
        $num = 0;

        foreach ($cnx->query($sql) as $row) {
            $num = $row['num'] + 1;
        }

        return $num;
    }

    static function ejecutaConsulta($sql) {
        $cnx = UtilDB::getConnection();
        $rst = $cnx->query($sql);
        return $rst;
    }

    static function ejecutaSQL($sql) {
        $cnx = UtilDB::getConnection();
        $count = $cnx->exec($sql);
        return $count;
    }

}