<?php
include("../backend/conexion.php");

$pedido_id = $_GET['pedido_id'] ?? 0;
$pedido = $conn->query("SELECT * FROM pedidos WHERE id = $pedido_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; text-align: center; padding-top: 60px; }
        .card { background: white; width: 400px; margin: auto; padding: 20px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #e91e63; }
        p { color: #555; }
        a { display: inline-block; margin-top: 20px; background: #e91e63; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; }
        a:hover { background: #c2185b; }
    </style>
</head>
<body>

<div class="card">
    <h1>✅ ¡Pedido Confirmado!</h1>
    <p>Tu número de pedido es: <strong>#<?= $pedido_id ?></strong></p>
    <p>Total: $<?= number_format($pedido['total'], 0, ',', '.') ?></p>
    <p>Estado: <?= $pedido['estado'] ?></p>
    <a href="catalogo.php">Volver al Catálogo</a>
</div>

</body>
</html>

