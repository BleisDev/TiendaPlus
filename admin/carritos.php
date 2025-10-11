<?php
session_start();
include('../backend/conexion.php');

// --- Agregar producto al carrito ---
if (isset($_GET['id_producto'])) {
    $id_producto = intval($_GET['id_producto']);

    $sql = "SELECT * FROM productos WHERE id = $id_producto";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();

        // Si el carrito no existe, lo creamos
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Si el producto ya está en el carrito, aumentamos la cantidad
        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto]['cantidad']++;
        } else {
            $producto['cantidad'] = 1;
            $_SESSION['carrito'][$id_producto] = $producto;
        }
    }
}

// --- Eliminar producto del carrito ---
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    unset($_SESSION['carrito'][$id_eliminar]);
}

// --- Actualizar cantidad ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $id_producto = intval($_POST['id_producto']);
    $nueva_cantidad = intval($_POST['cantidad']);
    if ($nueva_cantidad > 0) {
        $_SESSION['carrito'][$id_producto]['cantidad'] = $nueva_cantidad;
    }
}

// --- Obtener el carrito actual ---
$carrito = $_SESSION['carrito'] ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛍️ Carrito de Compras</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 20px;
        }
        .contenedor {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #e91e63;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #e91e63;
            color: white;
        }
        .btn {
            background-color: #e91e63;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #d81b60;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }
        .vacio {
            text-align: center;
            color: #777;
        }
        .acciones {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <h1>🛒 Carrito de Compras</h1>

    <?php if (empty($carrito)): ?>
        <p class="vacio">Tu carrito está vacío.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
            <?php
            $total = 0;
            foreach ($carrito as $item):
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['nombre']) ?></td>
                <td>$<?= number_format($item['precio'], 2) ?></td>
                <td>
                    <form method="POST" action="carrito.php">
                        <input type="hidden" name="id_producto" value="<?= $item['id'] ?>">
                        <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" min="1" style="width:60px;">
                        <button type="submit" class="btn">Actualizar</button>
                    </form>
                </td>
                <td>$<?= number_format($subtotal, 2) ?></td>
                <td class="acciones">
                    <a href="carrito.php?eliminar=<?= $item['id'] ?>" class="btn">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <p class="total">Total: $<?= number_format($total, 2) ?></p>
        <div style="text-align:center; margin-top:20px;">
            <a href="checkout.php" class="btn">Continuar al pago</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
