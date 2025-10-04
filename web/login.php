<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "TiendaPlus");
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}

$usuarioLogueado = null;
$error = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if ($usuario['rol'] === 'admin') {
            // Admin: contraseña en texto plano
            if ($password === $usuario['password']) {
                $_SESSION['id']     = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol']    = $usuario['rol'];
                $usuarioLogueado    = $usuario;
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            // Cliente: contraseña hasheada
            if (password_verify($password, $usuario['password'])) {
                $_SESSION['id']     = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol']    = $usuario['rol'];
                $usuarioLogueado    = $usuario;

                // Redirigir al panel principal de la tienda
                header("Location: index.php");
                exit();
            } else {
                $error = "Contraseña incorrecta";
            }
        }
    } else {
        $error = "Correo no registrado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login Tienda Plus</title>
<style>
body { font-family: Arial, sans-serif; padding: 20px; background-color: #f7f7f7; }
h2, h3 { text-align: center; }
form { max-width: 400px; margin: 40px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
button { padding: 10px; width: 100%; background-color: #ff69b4; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
button:hover { background-color: #ff85c1; }
p.error { color: red; text-align: center; }
div.panel { max-width: 1000px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
table th, table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
table th { background-color: #f2f2f2; }
</style>
</head>
<body>

<h2>Iniciar Sesión</h2>

<?php if($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if(!$usuarioLogueado): ?>
<form method="POST" action="">
    <label>Correo:</label>
    <input type="email" name="correo" required>
    <label>Contraseña:</label>
    <input type="password" name="password" required>
    <button type="submit">Ingresar</button>
</form>

<p style="text-align:center; margin-top:10px;">
    ¿No tienes cuenta? <a href="registro.php">Regístrate</a><br>
    <a href="#">¿Olvidaste tu contraseña?</a>
</p>

<?php else: ?>
    <h3>Bienvenido, <?= htmlspecialchars($usuarioLogueado['nombre']) ?></h3>

    <?php if($usuarioLogueado['rol'] === 'admin'): ?>
        <div class="panel">
            <h3>Panel de Administración</h3>

            <!-- Productos -->
            <h4>📦 Productos</h4>
            <table>
                <tr><th>ID</th><th>Nombre</th><th>Precio</th></tr>
                <?php
                $productos = $conn->query("SELECT id, nombre, precio FROM productos");
                while($row = $productos->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td>$<?= number_format($row['precio'],2) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Pedidos -->
            <h4>🛒 Pedidos</h4>
            <table>
                <tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Estado</th></tr>
                <?php
                $pedidos = $conn->query("
                    SELECT p.id, u.nombre AS nombre_cliente, p.fecha, p.total, p.estado
                    FROM pedidos p
                    JOIN usuarios u ON p.usuario_id = u.id
                ");
                while($row = $pedidos->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre_cliente']) ?></td>
                    <td><?= $row['fecha'] ?></td>
                    <td>$<?= number_format($row['total'],2) ?></td>
                    <td><?= $row['estado'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Usuarios -->
            <h4>👥 Usuarios</h4>
            <table>
                <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th></tr>
                <?php
                $usuarios = $conn->query("SELECT id, nombre, email, rol FROM usuarios");
                while($row = $usuarios->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= $row['rol'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Reseñas -->
            <h4>⭐ Reseñas</h4>
            <table>
                <tr><th>ID</th><th>Usuario</th><th>Producto</th><th>Comentario</th><th>Calificación</th><th>Fecha</th></tr>
                <?php
                $resenas = $conn->query("
                    SELECT r.id, u.nombre AS nombre_usuario, p.nombre AS nombre_producto,
                           r.comentario, r.calificacion, r.fecha
                    FROM resenas r
                    JOIN usuarios u ON r.usuario_id = u.id
                    JOIN productos p ON r.producto_id = p.id
                ");
                if($resenas){
                    while($row = $resenas->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre_usuario']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_producto']) ?></td>
                    <td><?= htmlspecialchars($row['comentario']) ?></td>
                    <td><?= $row['calificacion'] ?></td>
                    <td><?= $row['fecha'] ?></td>
                </tr>
                <?php
                    endwhile;
                } else {
                    echo "<tr><td colspan='6'>No hay reseñas disponibles</td></tr>";
                }
                ?>
            </table>

        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
$conn->close();
?>
</body>
</html>
