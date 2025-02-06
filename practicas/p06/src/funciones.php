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

function validar_edad_sexo($edad, $sexo) {
    if ($sexo == "femenino" && $edad >= 18 && $edad <= 35) {
        echo "<p style='color: green;'><strong>Bienvenida</strong>, usted está en el rango de edad permitido.</p>";
    } else {
        echo "<p style='color: red;'><strong>Error</strong>, no cumple con los criterios.</p>";
    }
}

function obtener_autos() {
    return [
        "ABC1234" => [
            "Auto" => ["marca" => "HONDA", "modelo" => 2020, "tipo" => "camioneta"],
            "Propietario" => ["nombre" => "Alfonso Esparza", "ciudad" => "Puebla, Pue.", "direccion" => "C.U., Jardines de San Manuel"]
        ],
        "XYZ5678" => [
            "Auto" => ["marca" => "MAZDA", "modelo" => 2019, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "María Molina", "ciudad" => "Puebla, Pue.", "direccion" => "97 Oriente"]
        ],
        "LMN3456" => [
            "Auto" => ["marca" => "TOYOTA", "modelo" => 2018, "tipo" => "hatchback"],
            "Propietario" => ["nombre" => "Juan Pérez", "ciudad" => "Guadalajara, Jal.", "direccion" => "Av. Vallarta 500"]
        ],
        "QWE7890" => [
            "Auto" => ["marca" => "FORD", "modelo" => 2022, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "Ana López", "ciudad" => "Monterrey, NL", "direccion" => "Calle Reforma 200"]
        ],
        "RTY1239" => [
            "Auto" => ["marca" => "NISSAN", "modelo" => 2021, "tipo" => "camioneta"],
            "Propietario" => ["nombre" => "Carlos Domínguez", "ciudad" => "CDMX", "direccion" => "Colonia Centro"]
        ],
        "UOP7654" => [
            "Auto" => ["marca" => "CHEVROLET", "modelo" => 2017, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "Laura Torres", "ciudad" => "Querétaro, Qro.", "direccion" => "Av. Universidad 150"]
        ],
        "ZXC6543" => [
            "Auto" => ["marca" => "BMW", "modelo" => 2023, "tipo" => "deportivo"],
            "Propietario" => ["nombre" => "Fernando Díaz", "ciudad" => "Mérida, Yuc.", "direccion" => "Zona Centro"]
        ],
        "BNM5671" => [
            "Auto" => ["marca" => "AUDI", "modelo" => 2020, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "Patricia Rojas", "ciudad" => "Veracruz, Ver.", "direccion" => "Malecón"]
        ],
        "VBN6782" => [
            "Auto" => ["marca" => "TESLA", "modelo" => 2022, "tipo" => "eléctrico"],
            "Propietario" => ["nombre" => "Javier Medina", "ciudad" => "León, Gto.", "direccion" => "Blvd. Aeropuerto"]
        ],
        "POI8765" => [
            "Auto" => ["marca" => "VOLKSWAGEN", "modelo" => 2019, "tipo" => "hatchback"],
            "Propietario" => ["nombre" => "Isabel Sánchez", "ciudad" => "Morelia, Mich.", "direccion" => "Centro Histórico"]
        ],
        "JKL4567" => [
            "Auto" => ["marca" => "MERCEDES", "modelo" => 2021, "tipo" => "SUV"],
            "Propietario" => ["nombre" => "Gustavo Herrera", "ciudad" => "San Luis Potosí, SLP.", "direccion" => "Col. Industrial"]
        ],
        "MNB6783" => [
            "Auto" => ["marca" => "KIA", "modelo" => 2016, "tipo" => "camioneta"],
            "Propietario" => ["nombre" => "Rocío Vargas", "ciudad" => "Toluca, Edo. Méx.", "direccion" => "Zona Centro"]
        ],
        "YUI2345" => [
            "Auto" => ["marca" => "HYUNDAI", "modelo" => 2015, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "Roberto Cervantes", "ciudad" => "Aguascalientes, Ags.", "direccion" => "Fraccionamiento San Marcos"]
        ],
        "GHJ7896" => [
            "Auto" => ["marca" => "PEUGEOT", "modelo" => 2018, "tipo" => "deportivo"],
            "Propietario" => ["nombre" => "Beatriz Olivares", "ciudad" => "Culiacán, Sin.", "direccion" => "Col. Guadalupe"]
        ],
        "WER5674" => [
            "Auto" => ["marca" => "RENAULT", "modelo" => 2017, "tipo" => "hatchback"],
            "Propietario" => ["nombre" => "Manuel Ramírez", "ciudad" => "Campeche, Camp.", "direccion" => "Col. Aviación"]
        ]
    ];
}

function mostrar_auto_por_matricula($autos, $matricula) {
    if (isset($autos[$matricula])) {
        echo "<h3>Información del Auto con Matrícula: $matricula</h3>";
        mostrar_info_auto($autos[$matricula]);
    } else {
        echo "<p style='color:red;'>No se encontró un auto con la matrícula $matricula.</p>";
    }
}

function mostrar_todos_los_autos($autos) {
    echo "<h3>Lista Completa de Autos</h3>";
    foreach ($autos as $matricula => $datos) {
        echo "<strong>Matrícula:</strong> $matricula <br>";
        mostrar_info_auto($datos);
        echo "<hr>";
    }
}

function mostrar_info_auto($datos) {
    echo "<strong>Marca:</strong> {$datos['Auto']['marca']}<br>";
    echo "<strong>Modelo:</strong> {$datos['Auto']['modelo']}<br>";
    echo "<strong>Tipo:</strong> {$datos['Auto']['tipo']}<br>";
    echo "<strong>Propietario:</strong> {$datos['Propietario']['nombre']}<br>";
    echo "<strong>Ciudad:</strong> {$datos['Propietario']['ciudad']}<br>";
    echo "<strong>Dirección:</strong> {$datos['Propietario']['direccion']}<br>";
}

?>