<?php
namespace Src;

use \PDO;
use \PDOException;

class Libros extends Conexion {
    private int $id_libro;
    private string $titulo;
    private string $isbn;
    private int $autor;
    private string $portada;

    public function __construct()
    {
        parent::__construct();
    }

    //--------------------------METODOS CRUD--------------------------
    public function create(){
        $q="insert into libros (titulo, isbn, autor, portada) values(:t, :i, :a, :p)";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':t'=>$this->titulo,
                ':i'=>$this->isbn,
                ':a'=>$this->autor,
                ':p'=>$this->portada
            ]);
        }catch(PDOException $ex){
            die("Error en crear " .$ex->getMessage());
        }
    }

    public function read(){
        $q="select * from libros where id_libro=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([':i'=>$this->id_libro]);
        }catch(PDOException $ex){
            die("Error en read" .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function update(int $id){
        $q="update libros set titulo=:t, isbn=:i, autor=:a, portada=:p where id_libro=:id";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':t'=>$this->titulo,
                ':i'=>$this->isbn,
                ':a'=>$this->autor,
                ':p'=>$this->portada,
                ':id'=>$id
            ]);
        }catch(PDOException $ex){
            die("Error en update " .$ex->getMessage());
        }
        parent::$conexion=null;
    }

    public static function delete(int $id){
        parent::crearConexion();
        $q="delete from libros where id_libro=:id";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([':id' => $id]);
        }catch(PDOException $ex){
            die("Error en delete " .$ex->getMessage());
        }
        parent::$conexion=null;
    }

    
    public static function readAll(){
        $q= "select *, nombre from libros, autores where autor=autores.id_autor order by libros.id_libro";
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

    //--------------------------OTROS METODOS-------------------------
    public function crearLibros(int $cant){
        if($this->hayLibros()) return;
        $faker = \Faker\Factory::create('es_ES');
        $autores = Autores::idAutores();
        for($i=0; $i<$cant; $i++){
            (new Libros)->setTitulo($faker->words(3, true))
            ->setIsbn($faker->isbn13())
            ->setAutor($faker->randomElement($autores))
            ->setPortada('/../public/img/default.png')
            ->create();
        }
    }

    private function hayLibros():bool{
        $q="select id_libro from libros";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error en hayLibros " .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    public static function existeId(int $id){
        parent::crearConexion();
        $q="select id_libro from libros where id_libro=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([':i'=>$id]);
        }catch(PDOException $ex){
            die("Error en existeId " .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    public static function existeIsbn(string $isbn, ?int $id=null){
        parent::crearConexion();
        $q =($id==null) ? "select id_libro from libros where isbn=:i" :
        "select id_libro from libros where isbn=:i and $id!=:id";
        $op=($id==null) ? [':i'=>$isbn] : [':i'=>$isbn, ':id'=>$id] ;
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute($op);
        }catch(PDOException $ex){
            die("Error en EXISTE ISBN Libro".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    //--------------------------SETTERS-------------------------------

    /**
     * Set the value of id_libro
     *
     * @return  self
     */ 
    public function setId_libro($id_libro)
    {
        $this->id_libro = $id_libro;

        return $this;
    }

    /**
     * Set the value of titulo
     *
     * @return  self
     */ 
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Set the value of isbn
     *
     * @return  self
     */ 
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Set the value of autor
     *
     * @return  self
     */ 
    public function setAutor($autor)
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Set the value of portada
     *
     * @return  self
     */ 
    public function setPortada($portada)
    {
        $this->portada = $portada;

        return $this;
    }
}