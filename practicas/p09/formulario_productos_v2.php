<?php
$servername = "localhost";
$username = "root";
$password = "Mitelefono12";
$database = "marketzone";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$producto = [];
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <style>
    body {
        background-image: url("https://mlti.com.mx/wp-content/uploads/2023/11/vista-superior-equipo-juego-1024x683.jpg");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #E0E0E0;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    h1, h2 {
        text-align: center;
        margin-top: 30px;
        color: #00BFFF;
        text-shadow: 2px 2px 10px #000;
    }

    form {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.8);
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 191, 255, 0.5);
        backdrop-filter: blur(5px);
    }

    label, input, select, textarea {
        display: block;
        width: 100%;
        margin-top: 10px;
    }

    input, select, textarea {
        padding: 10px;
        border: 2px solid #00BFFF;
        border-radius: 8px;
        background-color: #1A1A1A;
        color: #E0E0E0;
        font-size: 1rem;
        outline: none;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #00FFFF;
        box-shadow: 0 0 10px #00FFFF;
    }

    input[type="submit"] {
        padding: 10px 20px;
        background-color: #00BFFF;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        margin-top: 15px;
        transition: all 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #00FFFF;
        box-shadow: 0 5px 20px rgba(0, 255, 255, 0.6);
        transform: translateY(-3px);
    }

    .imagen-preview {
        text-align: center;
        margin-top: 20px;
    }

    .imagen-preview img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 191, 255, 0.5);
    }
    </style>
</head>
<body>
    <h1>Level Up Store</h1>
    <h2>Editar Producto</h2>
    <form action="update_producto.php" method="post">
        <input type="hidden" name="id" value="<?php echo $producto['id'] ?? ''; ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $producto['nombre'] ?? ''; ?>" required>

        <label for="marca">Marca:</label>
        <select id="marca" name="marca" required>
            <option value="">Seleccione una marca</option>
            <option value="Sony" <?php if (($producto['marca'] ?? '') == 'Sony') echo 'selected'; ?>>Sony</option>
            <option value="Microsoft" <?php if (($producto['marca'] ?? '') == 'Microsoft') echo 'selected'; ?>>Microsoft</option>
            <option value="Nintendo" <?php if (($producto['marca'] ?? '') == 'Nintendo') echo 'selected'; ?>>Nintendo</option>
            <option value="Razer" <?php if (($producto['marca'] ?? '') == 'Razer') echo 'selected'; ?>>Razer</option>
            <option value="Logitech" <?php if (($producto['marca'] ?? '') == 'Logitech') echo 'selected'; ?>>Logitech</option>
            <option value="Steam" <?php if (($producto['marca'] ?? '') == 'Steam') echo 'selected'; ?>>Steam</option>
            <option value="Otro" <?php if (($producto['marca'] ?? '') == 'Otro') echo 'selected'; ?>>Otro</option>
        </select>

        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" value="<?php echo $producto['modelo'] ?? ''; ?>" required>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" value="<?php echo $producto['precio'] ?? ''; ?>" required>

        <label for="detalles">Detalles:</label>
        <textarea id="detalles" name="detalles" rows="4"><?php echo $producto['detalles'] ?? ''; ?></textarea>

        <label for="unidades">Unidades:</label>
        <input type="number" id="unidades" name="unidades" value="<?php echo $producto['unidades'] ?? ''; ?>" required>

        <label for="imagen">Imagen (URL):</label>
        <input type="text" id="imagen" name="imagen" value="<?php echo $producto['imagen'] ?? ''; ?>">

        <?php if (!empty($producto['imagen'])): ?>
        <div class="imagen-preview">
            <img src="<?php echo $producto['imagen']; ?>" alt="Imagen del producto">
        </div>
        <?php endif; ?>

        <input type="submit" value="Actualizar Producto">
    </form>

    <p style="text-align:center; margin-top:20px;">
        <a href="get_productos_xhtml_v2.php?tope=1000" style="color:#00BFFF; text-decoration:none; font-weight:bold;">Ver todos los productos</a> |
        <a href="get_productos_vigentes_v2.php?tope=1000" style="color:#00BFFF; text-decoration:none; font-weight:bold;">Ver productos vigentes</a>
    </p>
</body>
</html>