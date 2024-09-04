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
        $fecha_inicio = $_POST["fecha_inicio"];
        $fecha_finalizacion = $_POST["fecha_finalizacion"];
        $estado = $_POST["estado"];
        $cliente_dni = $_POST["cliente_dni"];
        $descripcion = $_POST["descripcion"];
        
        $stmt = $conn->prepare("INSERT INTO Asunto (Fecha_inicio, Fecha_finalizacion, Estado, Cliente_DNI, Descripcion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fecha_inicio, $fecha_finalizacion, $estado, $cliente_dni, $descripcion);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<h2>Gestión de Asuntos</h2>

<?php if ($is_admin): ?>
<form method="POST" class="mb-4">
    <div class="form-group">
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="fecha_finalizacion">Fecha de Finalización:</label>
        <input type="date" id="fecha_finalizacion" name="fecha_finalizacion" class="form-control">
    </div>
    <div class="form-group">
        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="cliente_dni">DNI del Cliente:</label>
        <input type="text" id="cliente_dni" name="cliente_dni" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" class="form-control" rows="4"></textarea>
    </div>
    <button type="submit" name="crear" class="btn btn-primary">Crear Asunto</button>
</form>
<?php endif; ?>

<h3>Lista de Asuntos</h3>
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Número de Expediente</th>
            <th>Fecha de Inicio</th>
            <th>Fecha de Finalización</th>
            <th>Estado</th>
            <th>DNI del Cliente</th>
            <th>Descripción</th>
            <?php if ($is_admin): ?>
            <th>Acciones</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM Asunto");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Numero_expediente']) . "</td>
                    <td>" . htmlspecialchars($row['Fecha_inicio']) . "</td>
                    <td>" . htmlspecialchars($row['Fecha_finalizacion']) . "</td>
                    <td>" . htmlspecialchars($row['Estado']) . "</td>
                    <td>" . htmlspecialchars($row['Cliente_DNI']) . "</td>
                    <td>" . htmlspecialchars($row['Descripcion']) . "</td>";
            if ($is_admin) {
                echo "<td><a href='editar_asunto.php?numero_expediente=" . urlencode($row['Numero_expediente']) . "' class='btn btn-warning btn-sm'>Editar</a></td>";
            }
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
