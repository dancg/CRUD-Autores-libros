<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";
use Src\Libros;

if (!isset($_POST['libro'])) {
    header("Location:index.php");
    die();
}

//Aquí deserializamos la variable de autor
$libro = unserialize($_POST['libro']);

if (!Libros::existeId($libro->id_libro)) {
    header("Location:index.php");
    die();
}

//Borramos imagen si no es default.png
if(basename($libro->portada)!="default.png"){
    unlink(__DIR__."/..$libro->portada");
}

Libros::delete($libro->id_libro);
$_SESSION['mensaje'] = "El libro se ha borrado correctamente";
header("Location:index.php");