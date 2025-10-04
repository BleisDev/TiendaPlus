<?php
include("../includes/db.php");

$id = $_GET['id'];
$sql = "DELETE FROM productos WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: listar_productos.php?msg=Producto+eliminado");
} else {
    echo "Error: " . $conn->error;
}
?>
