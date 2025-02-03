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
    <h2>Ejercicio 3</h2>
    <p>Muestra el contenido de cada variable inmediatamente después de cada asignación,
       verificar la evolución del tipo de estas variables (imprime todos los componentes de los
       arreglo):</p>
    <p>$a = “PHP5”;</br>
       $z[] = &$a;</br>
       $b = “5a version de PHP”;</br>
       $c = $b*10;</br>
       $a .= $b;</br>
       $b *= $c;</br>
       $z[0] = “MySQL”;
    </p>
    <?php
        //AQUI VA MI CÓDIGO PHP
        echo '<h4>Respuesta:</h4>';
        $a = "PHP5";
        echo "<p>Valor de \$a: $a</p>";
        echo "<p>Tipo de \$a: " . gettype($a) . "</p>";

        $z[] = &$a;
        echo "<p>Valor de \$z: ";
        print_r($z);
        echo "</p>";
        echo "<p>Tipo de \$z: " . gettype($z) . "</p>";

        $b = "5a version de PHP";
        echo "<p>Valor de \$b: $b</p>";
        echo "<p>Tipo de \$b: " . gettype($b) . "</p>";

        $c = $b * 10;
        echo "<p>Valor de \$c: $c</p>";
        echo "<p>Tipo de \$c: " . gettype($c) . "</p>";

        $a .= $b;
        echo "<p>Valor de \$a después de concatenar: $a</p>";
        echo "<p>Tipo de \$a: " . gettype($a) . "</p>";

        $b *= $c;
        echo "<p>Valor de \$b después de multiplicar: $b</p>";
        echo "<p>Tipo de \$b: " . gettype($b) . "</p>";

        $z[0] = "MySQL";
        echo "<p>Valor de \$z después de modificar: ";
        print_r($z);
        echo "</p>";
        echo "<p>Tipo de \$z: " . gettype($z) . "</p>";

        unset($a, $b, $c, $z);
    ?>
    <h2>Ejercicio 4</h2>
    <p>Lee y muestra los valores de las variables del ejercicio anterior, pero ahora con la ayuda de
    la matriz $GLOBALS o del modificador global de PHP.</p>
    <?php
        //AQUI VA MI CÓDIGO PHP
        echo '<h4>Respuesta:</h4>';
        $a = "PHP5";
        $z[] = &$a;
        $b = "5a version de PHP";
        $c = $b * 10;
        $a .= $b;
        $b *= $c;
        $z[0] = "MySQL";

        // Usar $GLOBALS para acceder a las variables
        echo "<strong>Usando \$GLOBALS para mostrar los valores finales:</strong><br>";
        echo "Valor de \$a: " . $GLOBALS['a'] . "<br>";
        echo "Valor de \$b: " . $GLOBALS['b'] . "<br>";
        echo "Valor de \$c: " . $GLOBALS['c'] . "<br>";
        echo "Valor de \$z: ";
        print_r($GLOBALS['z']);

        // Liberar variables
        unset($a, $b, $c, $z);
    ?>
    <h2>Ejercicio 5</h2>
    <p>Dar el valor de las variables $a, $b, $c al final del siguiente script:</p>
    <p>$a = “7 personas”;</br>
       $b = (integer) $a;</br>
       $a = “9E3”;</br>
       $c = (double) $a;</br>
    </p>
    <?php
        //AQUI VA MI CÓDIGO PHP
        echo '<h4>Respuesta:</h4>';
        $a = "7 personas";
        $b = (integer) $a;
        $a = "9E3";
        $c = (double) $a;

        echo "Valor de \$a: $a (Tipo: " . gettype($a) . ")<br>";
        echo "Valor de \$b: $b (Tipo: " . gettype($b) . ")<br>";
        echo "Valor de \$c: $c (Tipo: " . gettype($c) . ")<br>";
        
        unset($a, $b, $c);
    ?>
    <h2>Ejercicio 6</h2>
    <p>Dar y comprobar el valor booleano de las variables $a, $b, $c, $d, $e y $f y muéstralas
    usando la función var_dump(<datos>).</p>
    <p>Después investiga una función de PHP que permita transformar el valor booleano de $c y $e
    en uno que se pueda mostrar con un echo:</p>
    <p>
    $a = “0”;</br>
    $b = “TRUE”;</br>
    $c = FALSE;</br>
    $d = ($a OR $b);</br>
    $e = ($a AND $c);</br>
    $f = ($a XOR $b);</br>
    </p>
    <?php
        //AQUI VA MI CÓDIGO PHP
        $a = "0";      
        $b = "TRUE";   
        $c = FALSE;    
        $d = ($a OR $b);  
        $e = ($a AND $c); 
        $f = ($a XOR $b); 
        echo '<h4>Respuesta:</h4>'; 
        echo "<h3>Valores booleanos:</h3>";
        echo "Valor de \$a: "; var_dump((bool)$a); echo "<br>"; 
        echo "Valor de \$b: "; var_dump((bool)$b); echo "<br>"; 
        echo "Valor de \$c: "; var_dump($c); echo "<br>"; 
        echo "Valor de \$d: "; var_dump($d); echo "<br>"; 
        echo "Valor de \$e: "; var_dump($e); echo "<br>"; 
        echo "Valor de \$f: "; var_dump($f); echo "<br>";  
        
        echo "<h3>Conversión de valores booleanos:</h3>";
        echo "Valor de \$c: " . var_export($c, true) . "<br>";
        echo "Valor de \$e: " . var_export($e, true) . "<br>";

        unset($a, $b, $c, $d, $e, $f);
    ?>
    <h2>Ejercicio 7</h2>
    <p>Usando la variable predefinida $_SERVER, determina lo siguiente:</p>
    <ul>
        <li>a. La versión de Apache y PHP.</li>
        <li>b. El nombre del sistema operativo (servidor).</li>
        <li>c. El idioma del navegador (cliente).</li>
    </ul>
    <?php
        //AQUI VA MI CÓDIGO PHP
        echo '<h4>Respuesta:</h4>';
        echo "<h3>Información del Servidor y Cliente</h3>";

        // a. Versión de Apache y PHP
        echo "<strong>Versión de Apache y PHP:</strong><br>";
        echo $_SERVER['SERVER_SOFTWARE'] . "<br><br>";

        // b. Nombre del sistema operativo del servidor
        echo "<strong>Nombre del Sistema Operativo del Servidor:</strong><br>";
        echo PHP_OS . "<br><br>";

        // c. Idioma del navegador del cliente
        echo "<strong>Idioma del Navegador del Cliente:</strong><br>";
        echo $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "<br>";
    ?>
</body>
</html>