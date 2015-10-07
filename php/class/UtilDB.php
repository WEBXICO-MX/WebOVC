<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once('UtilDB.php');

class UtilDB {

    private static $servername = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $database = "ovc";
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
        $rst = $cnx->query($sql);
        /* Comprobar el número de filas que coinciden con la sentencia SELECT */
        if ($rst->rowCount() > 0) {
            foreach ($rst->fetch() as $row) {
                $num = $row['num'] + 1;
            }
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