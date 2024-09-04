<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';

if (!isset($_GET['id_procurador'])) {
    header("Location: procurador.php");
    exit();
}

$id_procurador = $_GET['id_procurador'];

// Solo permitir modificaciones si es admin
$is_admin = $_SESSION['rol_id'] == 1;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $is_admin) {
    if (isset($_POST["actualizar"])) {
        $nombre = $_POST["nombre"];
        $direccion = $_POST["direccion"];
        
        $stmt = $conn->prepare("UPDATE Procurador SET Nombre=?, Direccion=? WHERE ID_procurador=?");
        $stmt->bind_param("ssi", $nombre, $direccion, $id_procurador);
        $stmt->execute();
        $stmt->close();
        
        header("Location: procurador.php");
        exit();
    } elseif (isset($_POST["eliminar"])) {
        $stmt = $conn->prepare("DELETE FROM Procurador WHERE ID_procurador=?");
        $stmt->bind_param("i", $id_procurador);
        $stmt->execute();
        $stmt->close();
        
        header("Location: procurador.php");
        exit();
    }
} else {
    // Obtener datos del procurador
    $stmt = $conn->prepare("SELECT * FROM Procurador WHERE ID_procurador=?");
    $stmt->bind_param("i", $id_procurador);
    $stmt->execute();
    $result = $stmt->get_result();
    $procurador = $result->fetch_assoc();
    $stmt->close();

    if (!$procurador) {
        echo "Procurador no encontrado.";
        exit();
    }
}
?>

<h2>Editar Procurador</h2>

<form method="POST">
    <div class="form-group">
        <label for="id_procurador">ID Procurador:</label>
        <input type="text" id="id_procurador" name="id_procurador" class="form-control" value="<?php echo htmlspecialchars($procurador['ID_procurador']); ?>" readonly>
    </div>
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($procurador['Nombre']); ?>" required>
    </div>
    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo htmlspecialchars($procurador['Direccion']); ?>" required>
    </div>
    <button type="submit" name="actualizar" class="btn btn-primary">Actualizar Procurador</button>
    <button type="submit" name="eliminar" class="btn btn-danger">Eliminar Procurador</button>
</form>

<?php include 'includes/footer.php'; ?>
