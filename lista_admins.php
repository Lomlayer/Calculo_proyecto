<?php
session_start();
require 'conexion.php';

// Verificar si el usuario está logueado y tiene rol de admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit;
}

// Si el formulario de ascender ha sido enviado, realizar el cambio de rol
if (isset($_GET['accion']) && isset($_GET['usuario_id'])) {
    $usuario_id = $_GET['usuario_id'];
    $accion = $_GET['accion']; // 'ascender'

    if ($accion == 'ascender') {
        $nuevo_rol = 1; // Rol de administrador
    }

    // Realizar la actualización en la base de datos
    $sql_update = "UPDATE usuarios SET rol_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param('ii', $nuevo_rol, $usuario_id);

    if ($stmt->execute()) {
        // Redirigir para evitar reenvío del formulario
        header("Location: index.php");
        exit;
    } else {
        echo "<p>Error al ascender al usuario.</p>";
    }
}

// Si el formulario de eliminar cuenta ha sido enviado, eliminar la cuenta del usuario
if (isset($_GET['accion']) && isset($_GET['usuario_id']) && $_GET['accion'] == 'eliminar') {
    $usuario_id = $_GET['usuario_id'];

    // Verificar que el usuario no sea el administrador logueado
    if ($usuario_id == $_SESSION['user_id']) {
        echo "<p style='color: red; text-align: center;'>No puedes eliminar tu propia cuenta, ya que eres administrador.</p>";
    } else {
        // Eliminar al usuario de la base de datos
        $sql_delete = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param('i', $usuario_id);

        if ($stmt->execute()) {
            // Redirigir después de la eliminación
            header("Location: index.php");
            exit;
        } else {
            echo "<p>Error al eliminar el usuario.</p>";
        }
    }
}

// Obtener todos los usuarios con su rol
$sql = "SELECT u.id, u.nombre, u.email, r.nombre AS rol FROM usuarios u JOIN roles r ON u.rol_id = r.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="css/admins.css">
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="left">
            <span>Administración de Usuarios</span>
        </div>
        <div class="right">
            <a href="index.php">Inicio</a>
            <a href="cerrar s.php">Cerrar Sesión</a>
        </div>
    </div>

    <!-- Mensajes de estado -->
    <?php if (isset($message)): ?>
        <div class="message <?php echo isset($message_type) ? $message_type : ''; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Tabla de Usuarios -->
    <table>
        <thead>
            <tr>
                <th>Nombre de Usuario</th>
                <th>Correo Electrónico</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rol = $row['rol'] == 'Admin' ? 'Administrador' : 'Usuario';
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . $rol . "</td>";
                    echo "<td class='acciones'>";
                    // Mostrar el botón de "Ascender" solo si el usuario no es un administrador
                    if ($row['rol'] != 'Admin') {
                        echo "<a href='?accion=ascender&usuario_id=" . $row['id'] . "'><button>Ascender</button></a>";
                    }
                    // Mostrar el botón de eliminar solo si no es el administrador logueado
                    if ($row['rol'] != 'Admin' || $row['id'] != $_SESSION['user_id']) {
                        echo "<a href='?accion=eliminar&usuario_id=" . $row['id'] . "' class='delete-btn'>";
                        echo " Eliminar Cuenta</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay usuarios disponibles.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Botón de regreso -->
    <a href="index.php" class="regresar-btn">Regresar</a>

</body>
</html>
