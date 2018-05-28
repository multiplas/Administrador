<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contador de tareas</title>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>
    <div class="container main-content">
        <?php
            include_once 'modulos/establecer_conexion.php';    
            include_once 'modulos/crear_tablas.php';    
        ?>
    </div>
</body>
</html>