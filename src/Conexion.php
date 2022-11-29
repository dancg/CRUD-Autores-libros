<?php
namespace Src;
use \PDO;
use \PDOException;

class Conexion{
    protected static $conexion;

    public function __construct()
    {
        self::crearConexion();
    }

    public static function crearConexion(){
        if(self::$conexion!=null) return;
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../");
        $dotenv->load();
        $usuario = $_ENV['USER'];
        $pass = $_ENV['PASS'];
        $host = $_ENV['HOST'];
        $db = $_ENV['DB'];

        $dns="mysql:host=$host;dbname=$db;charset=utf8mb4";
        $op=[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION];

        try{
            self::$conexion=new PDO($dns, $usuario, $pass, $op);
        }catch(PDOException $ex){
            die("Error en la conexion " .$ex->getMessage());
        }
    }
}