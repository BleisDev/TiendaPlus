<?php
include("../includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Manejo de la imagen
    $imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = "img/" . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../web/" . $imagen);
    }

    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $stock, $imagen);

    if ($stmt->execute()) {
        header("Location: listar_productos.php?msg=Producto+creado");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
