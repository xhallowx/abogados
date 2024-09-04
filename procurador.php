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
        $nombre = $_POST["nombre"];
        $direccion = $_POST["direccion"];
        
        $stmt = $conn->prepare("INSERT INTO Procurador (Nombre, Direccion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $direccion);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<h2>Gestión de Procuradores</h2>

<?php if ($is_admin): ?>
<form method="POST" class="mb-4">
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="form-control" required>
    </div>
    <button type="submit" name="crear" class="btn btn-primary">Crear Procurador</button>
</form>
<?php endif; ?>

<h3>Lista de Procuradores</h3>
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>ID Procurador</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <?php if ($is_admin): ?>
            <th>Acciones</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM Procurador");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['ID_procurador']) . "</td>
                    <td>" . htmlspecialchars($row['Nombre']) . "</td>
                    <td>" . htmlspecialchars($row['Direccion']) . "</td>";
            if ($is_admin) {
                echo "<td><a href='editar_procurador.php?id_procurador=" . urlencode($row['ID_procurador']) . "' class='btn btn-warning btn-sm'>Editar</a></td>";
            }
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
