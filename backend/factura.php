<?php
require_once('../backend/conexion.php');
session_start();

if (!isset($_GET['pedido_id'])) {
    echo "No se especificó un número de pedido.";
    exit;
}

$pedido_id = intval($_GET['pedido_id']);

// Obtener información del pedido
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE id=?");
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Obtener detalle del pedido
$stmt = $conn->prepare("SELECT p.nombre, d.cantidad, d.precio 
                        FROM detalle_pedido d 
                        JOIN productos p ON d.producto_id = p.id 
                        WHERE d.pedido_id=?");
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$detalles = $stmt->get_result();
$stmt->close();

if (!$pedido) {
    echo "Pedido no encontrado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Factura - Pedido #<?= $pedido_id ?> | TiendaPlus</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f8f9fc;
    margin: 0;
    padding: 0;
    color: #333;
}
header {
    background: linear-gradient(90deg, #ff69b4, #ff85c1);
    color: white;
    text-align: center;
    padding: 25px 0;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
header h1 {
    margin: 0;
    font-size: 26px;
    letter-spacing: 1px;
}
.contenedor {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.info {
    background: #fff7fb;
    border-left: 5px solid #ff69b4;
    padding: 15px 20px;
    margin-bottom: 25px;
    border-radius: 8px;
}
.info h2 {
    color: #ff69b4;
    margin-top: 0;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
th, td {
    padding: 12px 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
th {
    background: #ffedf5;
    color: #444;
}
tr:hover {
    background-color: #fff8fc;
}
.total {
    text-align: right;
    font-size: 20px;
    font-weight: 600;
    color: #ff4fa3;
    margin-top: 15px;
}
.botones {
    text-align: center;
    margin-top: 35px;
}
button, a.btn {
    background: linear-gradient(90deg, #ff69b4, #ff85c1);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}
button:hover, a.btn:hover {
    transform: translateY(-2px);
    background: linear-gradient(90deg, #ff5da6, #ff74b8);
}
a.volver {
    background: #6c757d;
}
a.volver:hover {
    background: #5a6268;
}
footer {
    text-align: center;
    margin-top: 40px;
    color: #888;
    font-size: 13px;
}
</style>
</head>
<body>

<header>
    <h1>🧾 TiendaPlus - Factura de Pedido #<?= $pedido_id ?></h1>
</header>

<div class="contenedor">
    <div class="info">
        <h2>📋 Detalles de facturación</h2>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['cliente']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($pedido['email']) ?></p>
        <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido['direccion']) ?></p>
        <p><strong>Método de pago:</strong> <?= htmlspecialchars($pedido['metodo_pago']) ?></p>
        <p><strong>Fecha:</strong> <?= htmlspecialchars($pedido['fecha']) ?></p>
    </div>

    <h2>🛍️ Tu pedido</h2>
    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Subtotal</th>
        </tr>
        <?php $total = 0; while ($row = $detalles->fetch_assoc()): 
            $subtotal = $row['cantidad'] * $row['precio'];
            $total += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= $row['cantidad'] ?></td>
            <td>$<?= number_format($row['precio'], 0, ',', '.') ?></td>
            <td>$<?= number_format($subtotal, 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="total">
        Total: $<?= number_format($total, 0, ',', '.') ?>
    </div>

    <div class="botones">
        <a href="javascript:window.print()" class="btn">🖨️ Descargar / Imprimir</a>
        <a href="../web/carrito.php" class="btn volver">⬅️ Volver al carrito</a>
        <a href="../web/index.php" class="btn volver">🏠 Volver a la tienda</a>
    </div>
</div>

<footer>
    © <?= date("Y") ?> TiendaPlus. Todos los derechos reservados.
</footer>

</body>
</html>
