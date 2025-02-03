<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Práctica 5</title>
</head>
<body>
    <h2>Ejercicio 1</h2>
    <p>Determina cuál de las siguientes variables son válidas y explica por qué:</p>
    <p>$_myvar,  $_7var,  myvar,  $myvar,  $var7,  $_element1, $house*5</p>
    <?php
        //AQUI VA MI CÓDIGO PHP
        $_myvar;
        $_7var;
        //myvar;       // Inválida
        $myvar;
        $var7;
        $_element1;
        //$house*5;     // Invalida
        
        echo '<h4>Respuesta:</h4>';   
    
        echo '<ul>';
        echo '<li>$_myvar es válida porque inicia con guión bajo.</li>';
        echo '<li>$_7var es válida porque inicia con guión bajo.</li>';
        echo '<li>myvar es inválida porque no tiene el signo de dolar ($).</li>';
        echo '<li>$myvar es válida porque inicia con una letra.</li>';
        echo '<li>$var7 es válida porque inicia con una letra.</li>';
        echo '<li>$_element1 es válida porque inicia con guión bajo.</li>';
        echo '<li>$house*5 es inválida porque el símbolo * no está permitido.</li>';
        echo '</ul>';
    ?>
    <h2>Ejercicio 2</h2>
    <p>Proporcionar los valores de $a, $b, $c como sigue:</br>
    $a = “ManejadorSQL”;</br>
    $b = 'MySQL’;</br>
    $c = &$a;</p>
    <p>a. Ahora muestra el contenido de cada variable</p>
    <p>b. Agrega al código actual las siguientes asignaciones:</br>
    $a = “PHP server”;</br>
    $b = &$a;</p>
    <p>c. Vuelve a mostrar el contenido de cada uno</p>
    <p>d. Describe en y muestra en la página obtenida qué ocurrió en el segundo bloque de
    asignaciones</p>
    <?php
        //AQUI VA MI CÓDIGO PHP
        // Asignaciones iniciales
        $a = "ManejadorSQL";
        $b = 'MySQL';
        $c = &$a;
        echo '<h4>Respuesta inciso a:</h4>';
        echo "<p>Valores iniciales:</p>";
        echo "a: $a <br />";
        echo "b: $b <br />";
        echo "c: $c <br />";
        echo '<h4>Cambio hecho del inciso b.</h4>'; 
        $a = "PHP server";
        $b = &$a;
        echo '<h4>Respuesta inciso c.</h4>';
        echo "<p>Valores después del cambio:</p>";
        echo "a: $a <br />";
        echo "b: $b <br />";
        echo "c: $c <br />";
        echo '<h4>Respuesta inciso d.</h4>';
        echo "<p>Explicación:</p>";
        echo "La variable \$c es una referencia a \$a, por lo que al cambiar \$a, también cambia \$c. \n";
        echo "Luego, \$b también se hace referencia a \$a, por lo que todas apuntan al mismo valor.";
        unset($a, $b, $c);
    ?>

</body>
</html>