<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";
use Src\Autores;

if (!isset($_POST['autor'])) {
    header("Location:index.php");
    die();
}

//Aquí deserializamos la variable de autor
$autor = unserialize($_POST['autor']);

if (!Autores::existeId($autor->id_autor)) {
    header("Location:index.php");
    die();
}

Autores::delete($autor->id_autor);
$_SESSION['mensaje'] = "El autor se ha borrado correctamente";
header("Location:index.php");