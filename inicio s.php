<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Usar declaración preparada para evitar inyección SQL
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Guardar los datos del usuario en la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['role'] = $user['rol_id'];
            // Redirigir a la página de inicio
            header("Location: index.php");
            exit();  // Asegurarse de que el script se detenga después de la redirección
        } else {
            echo "<p style='color: red; text-align: center;'>Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Usuario no encontrado.</p>";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        /* Estilo general del cuerpo */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #2f2f2f; /* Gris oscuro */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #f0f0f0;
        }

        /* Estilo del contenedor del formulario */
        .container {
            background-color: #333; /* Gris más oscuro para el contenedor */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            width: 400px;
        }

        h2 {
            color: #fff; /* Blanco para el texto del título */
            text-align: center;
            margin-bottom: 20px;
        }

        /* Estilo para los campos de entrada */
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #444;
            border-radius: 6px;
            font-size: 16px;
            background-color: #555; /* Fondo gris más claro */
            color: #f0f0f0;
        }

        input::placeholder {
            color: #bbb; /* Color gris claro para los placeholders */
        }

        /* Estilo para el contenedor de botones */
        .button-container {
            display: flex;
            gap: 15px;
        }

        /* Estilo para el botón de ingresar */
        button {
            flex: 1;
            padding: 12px;
            background-color: #007bff; /* Azul brillante */
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3; /* Azul más oscuro en hover */
        }

        /* Estilo para el botón de registrarse */
        .register-button {
            background-color: #444;
            color: #fff;
        }

        .register-button:hover {
            background-color: #555; /* Gris más claro en hover */
        }

        /* Estilo de mensaje de error */
        .error {
            color: #f44336; /* Rojo para los errores */
            text-align: center;
            margin-top: 20px;
        }

        /* Estilo de enlace para registro */
        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
        }

        .register-link a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Iniciar Sesión</h2>
    <form action="inicio s.php" method="post">
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <div class="button-container">
            <button type="submit">Ingresar</button>
        </div>
    </form>

    <!-- Si hay un error, se mostrará aquí -->
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($error_message))) { ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php } ?>

    <div class="register-link">
        <p>No tienes una cuenta? <a href="registro s.php">¡Regístrate aquí!</a></p>
    </div>
</div>

</body>
</html>
