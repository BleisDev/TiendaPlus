<?php
session_start();
require_once('../backend/conexion.php');

// Obtener categorías
$categorias = $conn->query("SELECT * FROM categorias ORDER BY nombre");

// Filtrar productos
$filtro = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
if ($filtro > 0) {
    $productos = $conn->query("SELECT * FROM productos WHERE categoria_id = $filtro");
} else {
    $productos = $conn->query("SELECT * FROM productos");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Catálogo de Productos - TiendaPlus</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #fafafa;
    margin: 0;
    padding: 0;
}
header {
    background: #ff69b4;
    padding: 18px 40px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
header h1 {
    margin: 0;
    font-size: 26px;
    display: flex;
    align-items: center;
    gap: 10px;
}
header h1::before {
    content: "🛍️";
}
header nav a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: 600;
    transition: opacity 0.3s;
}
header nav a:hover {
    opacity: 0.8;
}

/* Diseño general */
.container {
    display: flex;
    max-width: 1200px;
    margin: 40px auto;
    gap: 25px;
}

/* Panel lateral */
.sidebar {
    width: 250px;
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    height: fit-content;
}
.sidebar h3 {
    text-align: center;
    color: #ff69b4;
    margin-bottom: 20px;
}
.sidebar ul {
    list-style: none;
    padding: 0;
}
.sidebar ul li {
    margin-bottom: 10px;
}
.sidebar ul li a {
    display: block;
    padding: 10px 15px;
    background: #fff0f6;
    border-radius: 8px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
}
.sidebar ul li a:hover {
    background: #ff69b4;
    color: white;
}

/* Sección de productos */
.productos {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 25px;
}
.producto {
    background: white;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    text-align: center;
    position: relative;
}
.producto:hover {
    transform: scale(1.03);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.producto img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 10px;
}
.producto h3 {
    margin: 10px 0 5px;
    color: #333;
    font-weight: 600;
}
.precio {
    color: #ff69b4;
    font-weight: bold;
    font-size: 18px;
}
.btn-agregar {
    background: #ff69b4;
    border: none;
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    transition: background 0.3s;
    margin-top: 8px;
}
.btn-agregar:hover {
    background: #ff8fc6;
}

/* Footer */
footer {
    background: #ff69b4;
    color: white;
    text-align: center;
    padding: 20px;
    margin-top: 50px;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

/* Responsive */
@media (max-width: 900px) {
    .container {
        flex-direction: column;
        align-items: center;
    }
    .sidebar {
        width: 90%;
    }
}
</style>
</head>
<body>

<header>
    <h1>TiendaPlus</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="catalogo.php">Catálogo</a>
        <a href="carrito.php">Carrito 🛒</a>
    </nav>
</header>

<div class="container">
    <!-- Panel lateral -->
    <aside class="sidebar">
        <h3>Categorías</h3>
        <ul>
            <li><a href="catalogo.php" <?php if($filtro==0) echo 'style="background:#ff69b4;color:white"'; ?>>Todas</a></li>
            <?php while($cat = $categorias->fetch_assoc()): ?>
                <li>
                    <a href="catalogo.php?categoria=<?= $cat['id'] ?>"
                       <?php if($filtro==$cat['id']) echo 'style="background:#ff69b4;color:white"'; ?>>
                       <?= htmlspecialchars($cat['nombre']) ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </aside>

    <!-- Productos -->
    <section class="productos">
        <?php if ($productos->num_rows > 0): ?>
            <?php while ($p = $productos->fetch_assoc()): ?>
                <div class="producto">
                    <img src="../imagenes/<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
                    <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                    <p class="precio">$<?= number_format($p['precio'], 0, ',', '.') ?></p>
                <a href="detalle_producto.php?id=<?= $p['id'] ?>" class="btn-agregar">Ver detalle 🛍️</a>

                    
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay productos en esta categoría.</p>
        <?php endif; ?>
    </section>
</div>

<footer>
    © 2025 TiendaPlus - Todos los derechos reservados
</footer>

</body>
</html>
