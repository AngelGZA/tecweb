<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 4</title>
</head>
<body>
    <h2>Ejercicio 1</h2>
    <p>Escribir programa para comprobar si un número es un múltiplo de 5 y 7</p>
    <?php
    require_once __DIR__ .'/src/funciones.php';
        if(isset($_GET['numero']))
        {
            es_multiplo7y5($_GET['numero']);
        }
    ?>

    <h2>Ejemplo de POST</h2>
    <form action="http://localhost/tecweb/practicas/p06/index.php" method="post">
        Name: <input type="text" name="name"><br>
        E-mail: <input type="text" name="email"><br>
        <input type="submit">
    </form>
    <br>
    <?php
        if(isset($_POST["name"]) && isset($_POST["email"]))
        {
            echo $_POST["name"];
            echo '<br>';
            echo $_POST["email"];
        }
    ?>

    <h2>Ejercicio 2</h2>
    <p>Crea un programa para la generación repetitiva de 3 números aleatorios hasta obtener una
        secuencia compuesta por: impar, par, impar</p>
        <form method="post">
            <input type="submit" name="generar" value="Generar Matriz">
        </form>

    <?php
    require_once __DIR__ .'/src/funciones.php';

    if (isset($_POST['generar'])) {
        generar_matriz();
    }
    ?>

    <h2>Ejercicio 3</h2>
    <p>Utiliza un ciclo while para encontrar el primer número entero obtenido aleatoriamente,
    pero que además sea múltiplo de un número dado.</p>
    <form method="get">
        <label for="multiplo">Ingresa un número para encontrar su múltiplo:</label>
        <input type="number" name="multiplo" required>
        <input type="submit" value="Buscar Múltiplo">
    </form>

    <?php
    require_once __DIR__ . '/src/funciones.php';

    if (isset($_GET['multiplo']) && is_numeric($_GET['multiplo']) && $_GET['multiplo'] > 0) {
        $numero_dado = intval($_GET['multiplo']);

        echo "<h3>Usando ciclo WHILE</h3>";
        encontrar_multiplo_while($numero_dado);

        echo "<h3>Usando ciclo DO-WHILE</h3>";
        encontrar_multiplo_dowhile($numero_dado);
    }
    ?>
</body>
</html>