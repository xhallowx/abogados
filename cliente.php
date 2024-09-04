<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php'; 

// Solo permitir modificaciones si es admin
$is_admin = $_SESSION['rol_id'] == 1;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $is_admin) {
    if (isset($_POST["crear"])) {
        $dni = $_POST["dni"];
        $nombre = $_POST["nombre"];
        $direccion = $_POST["direccion"];

        // Validar datos antes de insertar
        if (!empty($dni) && !empty($nombre) && !empty($direccion)) {
            $stmt = $conn->prepare("INSERT INTO Cliente (DNI, Nombre, Direccion) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $dni, $nombre, $direccion);
            $stmt->execute();
            $stmt->close();

            // Redirigir para evitar reenvío del formulario
            header("Location: cliente.php");
            exit();
        } else {
            echo "<p class='text-danger'>Todos los campos son obligatorios.</p>";
        }
    }
}
?>

<h2>Gestión de Clientes</h2>

<?php if ($is_admin): ?>
<form method="POST" class="mb-4">
    <div class="form-group">
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="form-control" required>
    </div>
    <button type="submit" name="crear" class="btn btn-primary">Crear Cliente</button>
</form>
<?php endif; ?>

<h3>Lista de Clientes</h3>
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <?php if ($is_admin): ?>
            <th>Acciones</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM Cliente");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['DNI']) . "</td>
                    <td>" . htmlspecialchars($row['Nombre']) . "</td>
                    <td>" . htmlspecialchars($row['Direccion']) . "</td>";
            if ($is_admin) {
                echo "<td><a href='editar_cliente.php?dni=" . urlencode($row['DNI']) . "' class='btn btn-warning btn-sm'>Editar</a></td>";
            }
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
