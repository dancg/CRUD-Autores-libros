<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";

use Src\Autores;

//Métodos de la clase Autores
(new Autores)->crearAutor(10);
$autores = Autores::readAll();
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
    <title>Autores</title>
</head>

<body style="background-color: cyan;">
    <h5 class="text-center mt-4">Listado de Autores</h5>
    <div class="container">
        <a href= "./../libros/index.php" class="my-2 btn btn-success"><i class="fas fa-book"> Ir a Libros</i></a>
        <a href="crear.php" class="my-2 btn btn-primary"><i class="fas fa-add"> Crear Autor</i></a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($autores as $item) {
                    $autor = serialize($item);
                    //AL UTILIZAR SERIALIZE HAY QUE PONERLO CON COMILLAS SIMPLES
                    echo <<<TXT
                    <tr>
                    <th scope="row">{$item->id_autor}</th>
                    <td>{$item->nombre}</td>
                    <td>{$item->apellidos}</td>
                    <td>
                    <form class="form form-inline" action="borrar.php" method="POST">
                    <input type="hidden" name="autor" value='{$autor}' />
                    <a href="update.php?id={$item->id_autor}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i></a>
                    <button type ="submit" class="btn btn-danger btn-sm">
                    <i class= "fas fa-trash"></i></button>
                    </form>
                    </td>
                    </tr>
                    TXT;
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    if (isset($_SESSION['mensaje'])) {
        echo <<<TXT
        <script>
        Swal.fire({
            icon: 'success',
            title: '{$_SESSION['mensaje']}',
            showConfirmButton: false,
            timer: 1500
          })
        </script>
        TXT;
        unset($_SESSION['mensaje']);
    }
    ?>
</body>

</html>