<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    $sql = "INSERT INTO Usuarios (username, password, rol_id) VALUES ('$username', '$password', 2)";  // Rol de usuario por defecto
    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

include 'includes/header.php';
?>

<h2>Registro de Usuario</h2>
<form method="POST">
    <div class="form-group">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Registrar</button>
</form>

<p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>

<?php include 'includes/footer.php'; ?>
