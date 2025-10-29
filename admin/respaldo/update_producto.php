<?php
include("../includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Actualizar imagen si se sube una nueva
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = "img/" . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../web/" . $imagen);
        $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, imagen=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdisi", $nombre, $descripcion, $precio, $stock, $imagen, $id);
    } else {
        $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id);
    }

    if ($stmt->execute()) {
        header("Location: listar_productos.php?msg=Producto+actualizado");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
