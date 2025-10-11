<?php
if(session_status() === PHP_SESSION_NONE) session_start();
include_once('../backend/conexion.php');

$usuario_id = $_SESSION['id_usuario'] ?? null;

// Inicializar carrito si no existe
if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

// Paso 1: Carrito + formulario de pago
if(isset($_POST['pagar'])){
    $cliente = trim($_POST['cliente']);
    $email = trim($_POST['email']);
    $metodo_pago = $_POST['metodo_pago'] ?? 'Efectivo';

    // Validar datos
    if(empty($cliente) || empty($email)){
        $error = "Debes llenar todos los campos.";
    } else {
        // Calcular total
        $total = 0;
        foreach($_SESSION['carrito'] as $item){
            $total += $item['precio'] * $item['cantidad'];
        }

        // Insertar en tabla pedidos (maestro)
        $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, fecha, total, estado, cliente, email) VALUES (?, NOW(), ?, 'pendiente', ?, ?)");
        $stmt->bind_param("idss", $usuario_id, $total, $cliente, $email);
        $stmt->execute();
        $pedido_id = $stmt->insert_id;

        // Insertar en detalle_pedido
        $stmt_detalle = $conn->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        foreach($_SESSION['carrito'] as $id => $item){
            $stmt_detalle->bind_param("iiid", $pedido_id, $id, $item['cantidad'], $item['precio']);
            $stmt_detalle->execute();
        }

        // Limpiar carrito
        $_SESSION['carrito'] = [];

        // Redirigir a resumen
        header("Location: checkout.php?resumen=1&pedido_id=$pedido_id");
        exit;
    }
}

// Paso 3: Mostrar resumen si viene de redirección
$resumen = $_GET['resumen'] ?? 0;
$pedido_id = $_GET['pedido_id'] ?? 0;
if($resumen && $pedido_id){
    $res_pedido = $conn->query("SELECT * FROM pedidos WHERE id=$pedido_id")->fetch_assoc();
    $res_detalle = $conn->query("SELECT dp.*, p.nombre FROM detalle_pedido dp JOIN productos p ON dp.producto_id=p.id WHERE dp.pedido_id=$pedido_id");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Checkout - Tienda Plus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f8f9fa; font-family:Arial,sans-serif; }
.container { max-width:900px; margin:40px auto; }
.card { border-radius:12px; margin-bottom:20px; }
.thumb { width:80px; height:80px; object-fit:cover; border-radius:8px; }
.total { font-size:1.2rem; font-weight:bold; text-align:right; margin-top:20px; }
.btn-pink { background: #f08db2; color: white; font-weight: 600; border-radius: 30px; padding: 10px 20px; border: none; }
.btn-pink:hover { background: #e76da0; color: white; }
h2 { text-align:center; margin-bottom:30px; }
</style>
</head>
<body>
<div class="container">

<?php if($resumen && $pedido_id): ?>
    <!-- Paso 3: Resumen de Pedido -->
    <h2>✅ Resumen de tu Pedido #<?= $res_pedido['id'] ?></h2>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($res_pedido['cliente']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($res_pedido['email']) ?></p>
    <p><strong>Fecha:</strong> <?= $res_pedido['fecha'] ?></p>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; while($fila = $res_detalle->fetch_assoc()): 
                $subtotal = $fila['cantidad'] * $fila['precio_unitario'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                <td><?= $fila['cantidad'] ?></td>
                <td>$<?= number_format($fila['precio_unitario'],0,',','.') ?></td>
                <td>$<?= number_format($subtotal,0,',','.') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <h4 class="text-end">Total: $<?= number_format($total,0,',','.') ?></h4>
    <div class="d-flex justify-content-between mt-3">
        <a href="index.php" class="btn btn-secondary">⬅️ Volver a la tienda</a>
        <a href="factura.php?id=<?= $pedido_id ?>" class="btn btn-success">🧾 Descargar factura PDF</a>
    </div>

<?php else: ?>
    <!-- Paso 1: Mostrar Carrito + Formulario de Pago -->
    <h2>💳 Checkout / Pago</h2>

    <?php if(empty($_SESSION['carrito'])): ?>
        <div class="alert alert-info text-center">Tu carrito está vacío. <a href="index.php">Volver a la tienda</a></div>
    <?php else: ?>
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="card p-3 mb-4">
                <h4>Productos en tu carrito</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total=0; foreach($_SESSION['carrito'] as $id => $item): 
                            $subtotal = $item['precio'] * $item['cantidad'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                            <td><?= $item['cantidad'] ?></td>
                            <td>$<?= number_format($item['precio'],0,',','.') ?></td>
                            <td>$<?= number_format($subtotal,0,',','.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h5 class="text-end">Total: $<?= number_format($total,0,',','.') ?></h5>
            </div>

            <div class="card p-3">
                <h4>Datos de Pago</h4>
                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="cliente" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Método de pago</label>
                    <select name="metodo_pago" class="form-control">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                </div>

                <button type="submit" name="pagar" class="btn btn-pink w-100">Generar Venta</button>
            </div>
        </form>
    <?php endif; ?>
<?php endif; ?>

</div>
</body>
</html>
