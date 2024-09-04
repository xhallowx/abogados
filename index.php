<?php include 'includes/header.php'; ?>

<h1>Bienvenido al Sistema de Gestión del Gabinete de Abogados</h1>
<p class="lead">Seleccione una de las opciones del menú para gestionar los datos.</p>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Clientes</div>
            <div class="card-body">
                <p class="card-text">Gestione la información de los clientes.</p>
                <a href="cliente.php" class="btn btn-light">Ver Clientes</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Asuntos</div>
            <div class="card-body">
                <p class="card-text">Gestione los asuntos legales.</p>
                <a href="asunto.php" class="btn btn-light">Ver Asuntos</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">Procuradores</div>
            <div class="card-body">
                <p class="card-text">Gestione la información de los procuradores.</p>
                <a href="procurador.php" class="btn btn-light">Ver Procuradores</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
