<?php
session_start();

// Verificar que haya info de pedido
if (!isset($_SESSION['pedido_info'])) {
    header("Location: ../web/pedido_exitoso.php");
exit;
}

$pedido = $_SESSION['pedido_info'];

// Opcional: si quieres mostrar productos, podrías guardar un array en $_SESSION['pedido_info']['productos']
// Pero por ahora solo mostramos total, nombre, dirección y método de pago
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedido Confirmado - Tienda Plus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background:#fafafa; padding:30px; }
.card { max-width:600px; margin:auto; padding:20px; border-radius:12px; background:#fff; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
h1 { text-align:center; color:#28a745; }
p { font-size:16px; margin:8px 0; }
.btn-home { display:block; margin:auto; margin-top:20px; background:#ff69b4; color:white; padding:12px 20px; text-decoration:none; border-radius:8px; text-align:center; }
.btn-home:hover { background:#ff85c1; }
</style>
</head>
<body>

<div class="card">
    <h1>✅ Pedido Confirmado</h1>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($pedido['nombre']) ?></p>
    <p><strong>Dirección de envío:</strong> <?= htmlspecialchars($pedido['direccion']) ?></p>
    <p><strong>Método de pago:</strong> <?= htmlspecialchars($pedido['metodo_pago']) ?></p>
    <p><strong>Total a pagar:</strong> $<?= number_format($pedido['total'], 2, ',', '.') ?></p>

    <a href="index.php" class="btn-home">Volver a la tienda</a>
</div>

<?php
// Limpiar info del pedido después de mostrar
unset($_SESSION['pedido_info']);
?>
</body>
</html>
