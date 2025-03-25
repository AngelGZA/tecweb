<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    use EJEMPLOS\POO\Cabecera2 as Cabecera;
    require_once __DIR__ . '/Cabecera.php';
    
    /*
    $cab1 = new Cabecera('El Rincon del Programador', 'center');
    $cab1->graficar();
    */

    $cab1 = new Cabecera('El Rincon del Programador', 'center', 'https://www.deepseek.com');
    $cab1->graficar();

    ?>
</body>
</html>