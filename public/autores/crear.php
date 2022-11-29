<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";

use Src\Autores;

function mostrarError($nombre)
{
    if (isset($_SESSION[$nombre])) {
        echo "<p class='text-danger mt-2' style='font-size:0.8em'>{$_SESSION[$nombre]}</p>";
        unset($_SESSION[$nombre]);
    }
}

if (isset($_POST['btn'])) {
    $error = false;
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);

    if (strlen($nombre) < 3) {
        $error = true;
        $_SESSION['nombre'] = "**** El campo nombre debe tener como mínimo 3 caracteres";
        header("Location:{$_SERVER['PHP_SELF']}");
    }
    if (strlen($apellidos) < 6) {
        $error = true;
        $_SESSION['apellidos'] = "**** El campo apellidos debe tener como mínimo 6 caracteres";
        header("Location:{$_SERVER['PHP_SELF']}");
        die();
    }

    //Si no hay errores guardo el autor
    if(!$error){
        (new Autores)->setNombre($nombre)
        ->setApellidos($apellidos)
        ->create();

    $_SESSION['mensaje'] = "El autor se ha creado correctamente";
    header("Location:index.php");

    }
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
        <title>Nuevo Autor</title>
    </head>

    <body style="background-color: cyan;">
        <h5 class="text-center mt-4">Crear Autor</h5>
        <div class="container">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="mx-auto bg-secondary px-4 py-4 rounded" style="width:40rem;">
                <div class="mb-4">
                    <label for="n" class="form-label">Nombre Autor</label>
                    <?php
                    mostrarError('nombre');
                    ?>
                    <input type="text" id="n" class="form-control" placeholder="Nombre del autor" name="nombre" required />
                </div>
                <div class="mb-4">
                    <label for="a" class="form-label">Apellidos Autor</label>
                    <?php
                    mostrarError('apellidos');
                    ?>
                    <input type="text" id="a" class="form-control" placeholder="Apellidos del autor" name="apellidos" required />
                </div>
                <div>
                    <button type="submit" name="btn" class="btn btn-info">
                        <i class="fas fa-save"> Guardar</i>
                    </button>
                    <button type="reset" class="btn btn-warning">
                        <i class="fas fa-brush"> Limpiar</i>
                    </button>
                    <a href="index.php" class="btn btn-primary"> <i class="fas fa-backward"></i> Volver</a>
                </div>
            </form>
        </div>
    </body>

    </html>
<?php } ?>