<?php
session_start();
include_once("../backend/conexion.php");

if (!isset($_GET['id'])) {
    header("Location: catalogo.php");
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM productos WHERE id = $id");
if (!$result || $result->num_rows == 0) {
    echo "Producto no encontrado";
    exit;
}
$producto = $result->fetch_assoc();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cantidad = intval($_POST['cantidad']);
    if ($cantidad < 1) $cantidad = 1;

    $id = $producto['id'];
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
    } else {
        $_SESSION['carrito'][$id] = [
            'id' => $producto['id'],
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'imagen' => $producto['imagen'],
            'cantidad' => $cantidad
        ];
    }
    header("Location: detalle_producto.php?id=$id&added=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($producto['nombre']) ?> | TiendaPlus</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #fff;
    margin: 0;
    padding: 0;
}
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 40px;
    padding: 50px;
}
.card {
    background: #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border-radius: 20px;
    padding: 30px;
    max-width: 900px;
    display: flex;
    align-items: center;
    gap: 40px;
    animation: fadeIn 0.6s ease;
}
.card img {
    width: 300px;
    border-radius: 15px;
    transition: transform 0.4s ease;
}
.card img:hover {
    transform: scale(1.05);
}
.details {
    flex: 1;
}
.details h2 {
    color: #ff4ba8;
    margin-bottom: 10px;
}
.details p {
    color: #444;
    margin-bottom: 10px;
}
.price {
    font-size: 1.6rem;
    font-weight: bold;
    color: #ff4ba8;
    margin-bottom: 20px;
}
input[type="number"] {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 6px;
    width: 70px;
    text-align: center;
}
button {
    background-color: #ff4ba8;
    border: none;
    color: #fff;
    padding: 12px 20px;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: 600;
}
button:hover {
    background-color: #e63c93;
    transform: scale(1.05);
}
a {
    display: inline-block;
    text-decoration: none;
    color: #fff;
    background-color: #999;
    padding: 10px 20px;
    border-radius: 10px;
    margin-top: 15px;
    transition: 0.3s ease;
}
a:hover {
    background-color: #777;
    transform: scale(1.05);
}

/* --- Modal --- */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    display: none;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.4s ease;
    z-index: 999;
}
.modal {
    background: #fff;
    border-radius: 16px;
    padding: 25px 35px;
    text-align: center;
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    animation: zoomIn 0.4s ease;
}
.modal h3 {
    color: #ff4ba8;
    margin-bottom: 15px;
}
.modal p {
    color: #555;
    margin-bottom: 20px;
}
.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
}
.modal-buttons a {
    background-color: #ff4ba8;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}
.modal-buttons a:hover {
    background-color: #e63c93;
    transform: scale(1.05);
}
.modal-buttons .close-btn {
    background-color: #888;
}
.modal-buttons .close-btn:hover {
    background-color: #555;
}

/* Animaciones */
@keyframes fadeIn { from {opacity:0;} to {opacity:1;} }
@keyframes zoomIn {
    from {transform: scale(0.8); opacity: 0;}
    to {transform: scale(1); opacity: 1;}
}
</style>
</head>
<body>

<div class="container">
    <div class="card">
        <img src="../uploads/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
        <div class="details">
            <h2><?= htmlspecialchars($producto['nombre']) ?></h2>
            <p><?= htmlspecialchars($producto['descripcion']) ?></p>
            <div class="price">$<?= number_format($producto['precio'], 0, ',', '.') ?></div>

            <form method="POST">
                <label>Cantidad:</label>
                <input type="number" name="cantidad" value="1" min="1">
                <button type="submit">🛒 Agregar al carrito</button>
            </form>

            <a href="catalogo.php">⬅️ Volver al catálogo</a>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="modal-agregado">
    <div class="modal">
        <h3>🛍 Producto agregado</h3>
        <p>Tu producto se añadió correctamente al carrito.</p>
        <div class="modal-buttons">
            <a href="carrito.php">Ver carrito</a>
            <a href="#" class="close-btn" id="cerrar-modal">Seguir comprando</a>
        </div>
    </div>
</div>

<?php if (isset($_GET['added'])): ?>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modal-agregado");
    const closeBtn = document.getElementById("cerrar-modal");

    modal.style.display = "flex";
    closeBtn.addEventListener("click", (e) => {
        e.preventDefault();
        modal.style.display = "none";
    });
});
</script>
<?php endif; ?>

</body>
</html>
