<?php
session_start();
include("conexion.php");

// asumimos usuario logueado
if(!isset($_SESSION['user_id'])){
  http_response_code(401);
  echo json_encode(['error'=>'no_auth']);
  exit;
}
$usuario_id = $_SESSION['user_id'];

$producto_id = intval($_POST['producto_id'] ?? 0);
$cantidad = intval($_POST['cantidad'] ?? 1);
$op = $_POST['op'] ?? 'set'; // 'add' o 'set'

if($producto_id <= 0){
