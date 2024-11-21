<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9; /* Fondo claro */
            color: #333; /* Texto oscuro */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #2c3e50; /* Fondo gris oscuro */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            width: 400px;
        }

        h2 {
            color: #ecf0f1; /* Título blanco */
            text-align: center;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #666; /* Borde gris claro */
            border-radius: 5px;
            background-color: #555; /* Fondo de entrada gris */
            color: #fff; /* Texto blanco en los campos */
            font-size: 16px;
        }

        input::placeholder, select::placeholder {
            color: #bbb; /* Color gris claro para los placeholders */
        }

        input:focus, select:focus {
            border-color: #3498db; /* Bordes azules al enfocar */
            background-color: #666; /* Fondo más claro cuando se enfoca */
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff; /* Botón azul */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3; /* Botón azul más oscuro al pasar el mouse */
        }

        .back-button {
            width: 100%;
            padding: 12px;
            background-color: #28a745; /* Botón verde para el "Volver" */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }

        .back-button:hover {
            background-color: #218838; /* Verde más oscuro */
        }

        .admin-password-container {
            display: none; /* Oculto por defecto */
        }

        /* Mensajes de error y éxito */
        .message {
            text-align: center;
            font-size: 18px;
        }

        .message.success {
            color: #28a745;
        }

        .message.error {
            color: #dc3545;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Registro de Usuario</h2>
    <form action="Registro s.php" method="post">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        
        <!-- Campo de selección de rol -->
        <select name="rol_id" id="rol_id" required>
            <option value="" disabled selected>Selecciona un rol</option>
            <option value="1">Admin</option>
            <option value="2">Usuario</option>
        </select>

        <!-- Campo para la contraseña especial (solo visible cuando se selecciona "Admin") -->
        <div class="admin-password-container">
            <input type="password" name="admin_password" placeholder="Contraseña para Admin" />
        </div>

        <button type="submit">Registrar</button>
        <!-- Botón de regresar -->
        <a href="inicio s.php" class="back-button">Volver</a>
    </form>
</div>

<script>
    // Mostrar u ocultar el campo de la contraseña especial para Admin
    document.getElementById('rol_id').addEventListener('change', function() {
        var adminPasswordContainer = document.querySelector('.admin-password-container');
        if (this.value == '1') {
            adminPasswordContainer.style.display = 'block'; // Mostrar
        } else {
            adminPasswordContainer.style.display = 'none'; // Ocultar
        }
    });
</script>

<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol_id = $_POST['rol_id'];

    // Si el rol seleccionado es Admin, verificar la contraseña especial
    if ($rol_id == 1) {
        $admin_password = $_POST['admin_password'];
        $special_admin_password = "admin123"; // Aquí puedes poner la contraseña especial que quieras

        // Verificar que la contraseña especial sea correcta
        if ($admin_password !== $special_admin_password) {
            echo "<p class='message error'>Contraseña especial incorrecta para Admin.</p>";
            exit;
        }
    }

    // Usar declaración preparada para evitar inyección SQL
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $email, $password, $rol_id);

    if ($stmt->execute()) {
        echo "<p class='message success'>Usuario registrado exitosamente.</p>";
        echo "<p style='text-align: center;'><a href='inicio s.php'>Iniciar sesión</a></p>";
        
    } else {
        echo "<p class='message error'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
