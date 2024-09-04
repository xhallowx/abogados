<?php
include 'conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = md5($_POST["password"]); // Asegúrate de usar una técnica de hashing segura en producción

    $stmt = $conn->prepare("SELECT id, rol_id FROM Usuarios WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $rol_id);
        $stmt->fetch();
        $_SESSION['username'] = $username;
        $_SESSION['rol_id'] = $rol_id;

        // Obtener los IDs de rol de admin y procurador
        $admin_role_id_result = $conn->query("SELECT id FROM Roles WHERE rol_nombre = 'admin'");
        $admin_role_id = $admin_role_id_result->fetch_assoc()['id'];

        $procurador_role_id_result = $conn->query("SELECT id FROM Roles WHERE rol_nombre = 'procurador'");
        $procurador_role_id = $procurador_role_id_result->fetch_assoc()['id'];

        // Redirigir según el rol del usuario
        if ($rol_id == $admin_role_id) {
            header("Location: cliente.php");
        } elseif ($rol_id == $procurador_role_id) {
            header("Location: procurador_asuntos.php");
        } else {
            header("Location: index.php"); // Redirigir a una página por defecto si es necesario
        }
        exit();
    } else {
        echo "<p>Nombre de usuario o contraseña incorrectos.</p>";
    }
}

include 'includes/header.php';
?>

<h2>Inicio de Sesión</h2>
<form method="POST">
    <div class="form-group">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
</form>

<?php if (isset($error)): ?>
    <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
<?php endif; ?>

<p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.</p>

<?php include 'includes/footer.php'; ?>
