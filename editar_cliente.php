<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';

if (!isset($_GET['dni'])) {
    header("Location: cliente.php");
    exit();
}

$dni = $_GET['dni'];

// Solo permitir modificaciones si es admin
$is_admin = $_SESSION['rol_id'] == 1;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $is_admin) {
    if (isset($_POST["actualizar"])) {
        $nombre = $_POST["nombre"];
        $direccion = $_POST["direccion"];
        
        $stmt = $conn->prepare("UPDATE Cliente SET Nombre=?, Direccion=? WHERE DNI=?");
        $stmt->bind_param("sss", $nombre, $direccion, $dni);
        $stmt->execute();
        $stmt->close();
        
        header("Location: cliente.php");
        exit();
    } elseif (isset($_POST["eliminar"])) {
        $stmt = $conn->prepare("DELETE FROM Cliente WHERE DNI=?");
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $stmt->close();
        
        header("Location: cliente.php");
        exit();
    }
} else {
    // Obtener datos del cliente
    $stmt = $conn->prepare("SELECT * FROM Cliente WHERE DNI=?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    $stmt->close();

    if (!$cliente) {
        echo "Cliente no encontrado.";
        exit();
    }
}
?>

<h2>Editar Cliente</h2>

<form method="POST">
    <div class="form-group">
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" class="form-control" value="<?php echo htmlspecialchars($cliente['DNI']); ?>" readonly>
    </div>
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($cliente['Nombre']); ?>" required>
    </div>
    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo htmlspecialchars($cliente['Direccion']); ?>" required>
    </div>
    <button type="submit" name="actualizar" class="btn btn-primary">Actualizar Cliente</button>
    <button type="submit" name="eliminar" class="btn btn-danger">Eliminar Cliente</button>
</form>

<?php include 'includes/footer.php'; ?>
