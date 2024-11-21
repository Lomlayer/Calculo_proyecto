<?php
session_start();
include 'conexion.php';

// Verificación de sesión para asegurar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: inicio s.php");
    exit;
}

$role = $_SESSION['role'];  // Rol del usuario para la barra lateral
$user_name = $_SESSION['user_name'];  // Nombre del usuario para mostrarlo en la navbar

// Variable para almacenar el término de búsqueda
$search_term = '';
if (isset($_POST['search'])) {
    $search_term = $_POST['search'];
}

// Consulta para obtener los datos de la tabla
$sql = "SELECT * FROM tarjetas WHERE titulo LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%" . $search_term . "%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Tarjetas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: row;
        }

        .sidebar {
            height: 100vh; /* La barra lateral ocupa todo el alto de la pantalla */
            width: 250px; /* Ancho de la barra lateral */
            background-color: #2c3e50; /* Un tono más oscuro para la barra lateral */
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            box-sizing: border-box; /* Asegura que padding no afecte al tamaño total */
        }

        .sidebar a {
            color: #f0f0f0;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
            margin: 5px 0;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #476ea1;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }

        .navbar {
            background-color: #333;
            padding: 10px;
            color: #f0f0f0;
            display: flex;
            justify-content: space-between;
        }

        h1 {
            text-align: center;
            color: black;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            padding: 10px;
            border: 1px solid #444;
            border-radius: 4px;
            width: 300px;
            background-color: #555;
            color: #fff;
        }

        .search-form button {
            padding: 10px 16px;
            border: none;
            background-color: #444;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
            margin-left: 10px;
        }

        .search-form button:hover {
            background-color: #666;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #2d3b44;
            border-radius: 8px;
            overflow: hidden;
        }

        table, th, td {
            border: 1px solid #444;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #34495e;
            color: #fff;
            font-size: 1.1em;
        }

        td {
            background-color: #1c2833;
            color: #d5d8dc;
            font-size: 1em;
        }

        td a {
            color: #f39c12;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.9em;
        }

        td a:hover {
            color: #e67e22;
        }

        tr:hover {
            background-color: #2c3e50;
        }

        .no-results {
            text-align: center;
            color: #fff;
            font-size: 1.2em;
            margin-top: 20px;
        }

        .back-button {
            display: block;
            width: 150px;
            padding: 12px;
            background-color: #444;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            margin: 20px auto;
            text-transform: uppercase;
            font-weight: bold;
        }

        .back-button:hover {
            background-color: #666;
        }

        .navbar a {
            text-decoration: none;
            margin: 0 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="index.php">Inicio</a>
    <?php if ($role == 1) { ?>
        <a href="registro.php">Agregar Tarjeta</a>
        <a href="lista_admins.php">Lista de Admins</a>
    <?php } ?>
    <a href="Buscar.php">Buscar</a>
    <a href="temario.php" class="temario">Temario</a>
</div>

<!-- Contenido -->
<div class="content">
    <div class="navbar">
        <span>Buscar Tema</span>
        <a href="cerrar.php">Cerrar Sesión</a>
    </div>

    <div class="container">
        <h1>Buscar Temas</h1>

        <!-- Botón de regreso -->
        <a href="index.php" class="back-button">Regresar</a>

        <form class="search-form" method="POST" action="">
            <input type="text" name="search" placeholder="Buscar por título" value="<?= htmlspecialchars($search_term) ?>">
            <button type="submit">Buscar</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Enlace Video 1</th>
                        <th>Enlace Video 2</th>
                        <th>Enlace Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['titulo'] ?></td>
                            <td><?= nl2br(htmlspecialchars($row['descripcion'])) ?></td>
                            <td><a href="<?= $row['enlace_video1'] ?>" target="_blank"><?= $row['enlace_video1'] ?></a></td>
                            <td><a href="<?= $row['enlace_video2'] ?>" target="_blank"><?= $row['enlace_video2'] ?></a></td>
                            <td>
                                <?php if (isset($row['enlace_nota'])): ?>
                                    <a href="<?= $row['enlace_nota'] ?>" target="_blank"><?= $row['enlace_nota'] ?></a>
                                <?php else: ?>
                                    No disponible
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-results">No se encontraron resultados.</p>
        <?php endif; ?>

    </div>
</div>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
