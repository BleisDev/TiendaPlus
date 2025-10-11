<?php
include_once("../backend/conexion.php");

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'] ?? '';
$precio = floatval($_POST['precio']);
$stock = intval($_POST['stock']);
$categoria_id = $_POST['categoria_id'] ?: NULL;

// subir imagen
$imagen_path = NULL;
if(!empty($_FILES['imagen']['name'])) {
    $target_dir = "../web/img/products/";
    if(!is_dir($target_dir)) mkdir($target_dir,0755,true);
    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $file = uniqid('p_').'.'.$ext;
    if(move_uploaded_file($_FILES['imagen']['tmp_name'], $target_dir.$file)) {
        $imagen_path = "img/products/".$file;
    }
}

$stmt = $conn->prepare("INSERT INTO productos (nombre,descripcion,precio,stock,categoria_id,imagen,fecha_creacion) VALUES (?,?,?,?,?,?,NOW())");
$stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $stock, $categoria_id, $imagen_path);
$stmt->execute();

header("Location: productos.php");
exit;
