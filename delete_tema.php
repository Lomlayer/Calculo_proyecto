<?php
session_start();
require 'conexion.php'; // Conexión a la base de datos

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit;
}

// Verificar si se pasó un ID de tema para eliminar
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: temario.php");
    exit;
}

$id = $_GET['id'];

// Eliminar el tema de la base de datos
$delete_sql = "DELETE FROM temas WHERE id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("i", $id);
$delete_stmt->execute();

// Redirigir al listado de temas después de eliminar
header("Location: temario.php");
exit;
?>

