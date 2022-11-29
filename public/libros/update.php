<?php

session_start();
require __DIR__ . "./../../vendor/autoload.php";
if (!isset($_GET['id'])) {
    header("Location:index.php");
    die();
}
$id = $_GET['id'];

use Src\{Libros, Autores, Tools};

//Comprobamos si el id existe
if (!Libros::existeId($id)) {
    header("Location:index.php");
    die();
}

$idAutores = Autores::idAutores(); //Aqui cojo un array de todos los nombres de autores
$autores = Autores::readAll(1);
$libro = (new Libros)->setId_libro($id)
    ->read();

function mostrarError($error)
{
    if (isset($_SESSION[$error])) {
        echo "<p class='text-danger' style='0.8rem'>{$_SESSION[$error]}</p>";
        unset($_SESSION[$error]);
    }
}

if (isset($_POST['btn'])) {
    $isbnaux = $libro->isbn;
    $error = false;
    $titulo = trim($_POST['titulo']);
    $isbn = trim($_POST['isbn']);
    $autor = $_POST['autor'];

    //Comprobamos los campos

    if (strlen($titulo) < 5) {

        $error = true;
        $_SESSION['titulo'] = "**** El titulo debe tener al menos 5 caracteres";
    }

    if (Libros::existeIsbn($isbn, $id)) {
        $error = true;
        $_SESSION['isbn'] = "**** El isbn insertado ya existe en la base de datos";
    }

    if (!in_array($autor, $idAutores)) {
        $error = true;
        $_SESSION['autor'] = "**** El autor propuesto no se encuentra en la base de datos";
    }

    if (!preg_match('/^[0-9]{13}/', $isbn)) {
        $error = true;
        $_SESSION['isbn'] = "**** Formato del isbn incorrecto, debe ser de 13 números";
    }

    $control = false;

    foreach ($autores as $unAutor) {
        if ($unAutor->id_autor == $autor) {
            $control = true;
            break;
        }
    }
    if (!$control) {
        $_SESSION['autor'] = "**** El autor insertado no se encuentra en la base de datos";
        $error = true;
    }

    //Si hay error nos manda a la misma página mostrando errores
    if ($error) {
        header("Location:{$_SERVER['PHP_SELF']}?id=$id");
        die();
    }

    // Comprobar portada
    $portada = Tools::getImages();

    $nombrePortada = $libro->portada;

    if ($_FILES['portada']['error'] == 0) {
        if (!in_array($_FILES['portada']['type'], $portada)) {
            //Si llegamos aqui significa que lo que he subido no es un tipo de imagen 
            $_SESSION['portada'] = "**** Se esperaba una imagen";
            header("Location:{$_SERVER['PHP_SELF']}?id=$id");
            die();
        }
        $nombrePortada = "/img/" . uniqid() . "-" . "{$_FILES['portada']['name']}";
        if (!move_uploaded_file($_FILES['portada']['tmp_name'], __DIR__ . "/..$nombrePortada")) {
            $nombrePortada = $libro->portada;
        } else {
            if (basename($libro->portada) != "default.png") {
                unlink(__DIR__ . "/..{$libro->portada}");
            }
        }
    }

    //Si todo ha ido bien creo el libro
    (new Libros)->setTitulo($titulo)
        ->setIsbn($isbn)
        ->setAutor($autor)
        ->setPortada($nombrePortada)
        ->update($id);

    $_SESSION['mensaje'] = "Se ha actualizado el libro";
    header("Location:index.php");
} else {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Bootsrap 5.2 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <!-- sweetalert2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <title>Actualizar Libro</title>
    </head>

    <body style="background-color:red">

        <h5 class="text-center mt-4">Editar Libro</h5>
        <div class="container">
            <form name='as' method='POST' action="<?php echo $_SERVER['PHP_SELF'] . "?id=$id" ?>" enctype="multipart/form-data" class="py-4 px-4 mx-auto text-light bg-dark rounded">

                <div class="row">
                    <div class="col">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" placeholder="Titulo" name="titulo" value=" <?php echo $libro->titulo ?>">
                        <?php
                        MostrarError('titulo')
                        ?>
                    </div>
                    <div class="col">
                        <label for="isbn">ISBN</label>
                        <input type="number" class="form-control" placeholder="Pon ISBN de 13 números" name="isbn" min='0' max='9999999999999' value="<?php echo $libro->isbn ?>">
                        <?php
                        MostrarError('isbn')
                        ?>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <select name="autor" class="form-select">
                            <?php
                        foreach ($autores as $unAutor) {
                            $control = ($libro->autor==$unAutor->id_autor) ? "selected" : "";
                            echo "<option $control value='{$unAutor->id_autor}'>$unAutor->nombre</option>";
                        }
                        ?>
                        </select>
                        <?php
                        MostrarError('autor')
                        ?>
                    </div>
                    <div class="mt-4">
                        <div class="row">
                            <div class="col">
                                <div class="input-group">
                                    <input type="file" class="form-control" id="file" accept="image/*" name='portada'>
                                    <?php
                                    MostrarError('portada')
                                    ?>
                                </div>

                            </div>
                            <div class="mt-4 text-center">
                                <img class="img-thumbnail" src="..<?php echo $libro->portada ?>" id="image" style="width:10rem; height:10rem" />
                            </div>

                            <div>
                                <a href="index.php" class="btn btn-primary">
                                    <i class="fas fa-backward"></i> Volver
                                </a>

                                <button type="submit" name="btn" class="btn btn-info">
                                    <i class="fa fa-edit"></i> Editar
                                </button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>

        <script>
            document.getElementById("file").addEventListener('change', cambiarImagen);

            function cambiarImagen(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById('image').setAttribute('src', event.target.result)
                };
                reader.readAsDataURL(file);
            }
        </script>
    </body>

    </html>
<?php } ?>