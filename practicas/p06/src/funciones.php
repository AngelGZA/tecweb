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

?>