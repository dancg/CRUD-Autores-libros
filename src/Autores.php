<?php
namespace Src;
use \PDO;
use \PDOException;

class Autores extends Conexion{
    private int $id_autor;
    private string $nombre;
    private string $apellidos;

    public function __construct()
    {
        parent::__construct();
    }

    //-------------------------------METODOS CRUD------------------------------------
    public function create(){
        $q="insert into autores(nombre, apellidos) values(:n, :a)";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':a'=>$this->apellidos
            ]);
        }catch(PDOException $ex){
            die("Error en crear " .$ex->getMessage());
        }
        parent::$conexion=null;
    }

    public function read(){
        $q="select * from autores where id_autor=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([':i'=>$this->id_autor]);
        }catch(PDOException $ex){
            die("Error en read" .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function update(int $id){
        $q="update autores set nombre=:n, apellidos=:a where id_autor=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':a'=>$this->apellidos,
                ':i'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error en update " .$ex->getMessage());
        }
        parent::$conexion=null;
    }

    public static function delete(int $id){
        $q="delete from autores where id_autor=:i";
        parent::crearConexion();
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([':i'=>$id]);
        }catch(PDOException $ex){
            die("Error en delete" .$ex->getMessage());
        }
        parent::$conexion=null;
    }

    public static function readAll(?int $modo=null){
        $q=($modo==null) ? "select * from autores" : "select id_autor, nombre from autores";
        parent::crearConexion();
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error en readAll" .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //-------------------------------OTROS METODOS-----------------------------------
    public function crearAutor($cant){
        if($this->hayAutores()) return;
        $faker = \Faker\Factory::create('es_ES');
        for($i=0; $i<$cant; $i++){
            (new Autores)->setNombre($faker->firstName())
            ->setApellidos($faker->lastName() ." ". $faker->lastName())
            ->create();
        }
    }

    public function hayAutores():bool{
        $q="select id_autor from autores";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error en hayAutores " .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    public static function existeId($id):bool{
        parent::crearConexion();
        $q="select id_autor from autores where id_autor=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([':i'=>$id]);
        }catch(PDOException $ex){
            die("Error en existeId " .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    public static function idAutores() :array{
        parent::crearConexion();
        $q = "select id_autor from autores";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en idAutores " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    //-------------------------------SETTERS-----------------------------------------

 
    /**
     * Set the value of id_autor
     *
     * @return  self
     */ 
    public function setId_autor($id_autor)
    {
        $this->id_autor = $id_autor;

        return $this;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of apellidos
     *
     * @return  self
     */ 
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

}