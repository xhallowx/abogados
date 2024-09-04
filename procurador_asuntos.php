<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';

// Solo permitir acceso a procuradores
// Obtener el rol de procurador
$result = $conn->query("SELECT id FROM Roles WHERE rol_nombre = 'procurador'");
$procurador_role_id = $result->fetch_assoc()['id'];

$is_procurador = $_SESSION['rol_id'] == $procurador_role_id;

if (!$is_procurador) {
    echo "<p>No tienes permisos para acceder a esta página.</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["actualizar"])) {
        $numero_expediente = $_POST["numero_expediente"];
        $fecha_inicio = $_POST["fecha_inicio"];
        $fecha_finalizacion = $_POST["fecha_finalizacion"];
        $estado = $_POST["estado"];
        $cliente_dni = $_POST["cliente_dni"];
        $descripcion = $_POST["descripcion"];
        
        $stmt = $conn->prepare("UPDATE Asunto SET Fecha_inicio=?, Fecha_finalizacion=?, Estado=?, Cliente_DNI=?, Descripcion=? WHERE Numero_expediente=?");
        $stmt->bind_param("sssssi", $fecha_inicio, $fecha_finalizacion, $estado, $cliente_dni, $descripcion, $numero_expediente);
        $stmt->execute();
        $stmt->close();
        
        header("Location: procurador_asuntos.php");
        exit();
    }
}
?>

<h2>Gestión de Asuntos para Procuradores</h2>

<h3>Lista de Asuntos</h3>
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Numero Expediente</th>
            <th>Fecha de Inicio</th>
            <th>Fecha de Finalización</th>
            <th>Estado</th>
            <th>Cliente DNI</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM Asunto");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Numero_expediente']) . "</td>
                    <td>" . htmlspecialchars($row['Fecha_inicio']) . "</td>
                    <td>" . htmlspecialchars($row['Fecha_finalizacion']) . "</td>
                    <td>" . htmlspecialchars($row['Estado']) . "</td>
                    <td>" . htmlspecialchars($row['Cliente_DNI']) . "</td>
                    <td>" . htmlspecialchars($row['Descripcion']) . "</td>
                    <td><a href='editar_asunto.php?numero_expediente=" . urlencode($row['Numero_expediente']) . "' class='btn btn-warning btn-sm'>Editar</a></td>
                  </tr>";
        }
        ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
