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

if (!$is_admin) {
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
        
        header("Location: asunto.php");
        exit();
    }
}

if (isset($_GET["numero_expediente"])) {
    $numero_expediente = $_GET["numero_expediente"];
    $stmt = $conn->prepare("SELECT * FROM Asunto WHERE Numero_expediente=?");
    $stmt->bind_param("i", $numero_expediente);
    $stmt->execute();
    $result = $stmt->get_result();
    $asunto = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "<p>No se ha especificado el número de expediente.</p>";
    exit();
}
?>

<h2>Editar Asunto</h2>
    
<form method="POST">
    <div class="form-group">
        <label for="numero_expediente">Número de Expediente:</label>
        <input type="text" id="numero_expediente" name="numero_expediente" class="form-control" value="<?php echo htmlspecialchars($asunto['Numero_expediente']); ?>" readonly>
    </div>
    <input type="hidden" name="numero_expediente" value="<?php echo htmlspecialchars($asunto['Numero_expediente']); ?>">
    <div class="form-group">
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo htmlspecialchars($asunto['Fecha_inicio']); ?>" required>
    </div>
    <div class="form-group">
        <label for="fecha_finalizacion">Fecha de Finalización:</label>
        <input type="date" id="fecha_finalizacion" name="fecha_finalizacion" class="form-control" value="<?php echo htmlspecialchars($asunto['Fecha_finalizacion']); ?>">
    </div>
    <div class="form-group">
        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" class="form-control" value="<?php echo htmlspecialchars($asunto['Estado']); ?>" required>
    </div>
    <div class="form-group">
        <label for="cliente_dni">DNI del Cliente:</label>
        <input type="text" id="cliente_dni" name="cliente_dni" class="form-control" value="<?php echo htmlspecialchars($asunto['Cliente_DNI']); ?>" required>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" class="form-control" rows="4"><?php echo htmlspecialchars($asunto['Descripcion']); ?></textarea>
    </div>
    <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
    <a href="asunto.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include 'includes/footer.php'; ?>
