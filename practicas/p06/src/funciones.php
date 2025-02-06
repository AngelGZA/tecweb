<?php
function es_multiplo7y5($num){
            if ($num%5==0 && $num%7==0)
            {
                echo '<h3>R= El número '.$num.' SÍ es múltiplo de 5 y 7.</h3>';
            }
            else
            {
                echo '<h3>R= El número '.$num.' NO es múltiplo de 5 y 7.</h3>';
            }
}

function generar_matriz() {
    $matriz = [];
    $iteraciones = 0;
    $total_numeros = 0;

    do {
        $fila = [
            rand(100, 999),
            rand(100, 999),
            rand(100, 999)
        ];
        
        $matriz[] = $fila;
        $iteraciones++;
        $total_numeros += 3;

        $condicion = ($fila[0] % 2 != 0) && ($fila[1] % 2 == 0) && ($fila[2] % 2 != 0);
        
    } while (!$condicion);
    echo "<h2>Matriz generada:</h2>";
    echo "<table border='1' cellpadding='5'>";
    foreach ($matriz as $fila) {
        echo "<tr>";
        foreach ($fila as $num) {
            echo "<td>$num</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    echo "<p><strong>$total_numeros</strong> números obtenidos en <strong>$iteraciones</strong> iteraciones.</p>";
}

function encontrar_multiplo_while($num) {
    $contador = 0;
    $numero_aleatorio = rand(1, 1000);

    while ($numero_aleatorio % $num !== 0) {
        $numero_aleatorio = rand(1, 1000);
        $contador++;
    }

    echo "Primer múltiplo encontrado: <strong>$numero_aleatorio</strong> después de $contador intentos.<br>";
}

function encontrar_multiplo_dowhile($num) {
    $contador = 0;
    do {
        $numero_aleatorio = rand(1, 1000);
        $contador++;
    } while ($numero_aleatorio % $num !== 0);

    echo "Primer múltiplo encontrado: <strong>$numero_aleatorio</strong> después de $contador intentos.<br>";
}

function generar_arreglo_ascii() {
    $arreglo = [];
    for ($i = 97; $i <= 122; $i++) {
        $arreglo[$i] = chr($i);
    }
    return $arreglo;
}

function mostrar_tabla_ascii($arreglo) {
    echo '<table border="1" cellpadding="5">';
    echo '<tr><th>Índice ASCII</th><th>Letra</th></tr>';
    
    foreach ($arreglo as $key => $value) {
        echo "<tr><td>$key</td><td>$value</td></tr>";
    }
    
    echo '</table>';
}
?>