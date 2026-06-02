<?php
// clientes.php
require 'db.php';
$mensaje = '';

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    try {
        $conn->query("DELETE FROM cliente WHERE id_cliente = $id");
        header("Location: clientes.php"); exit;
    } catch (Exception $e) {
        $mensaje = "<div class='alert alert-danger'>No se puede eliminar el cliente. Tiene pedidos registrados.</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    
    if ($_POST['accion'] == 'crear') {
        $conn->query("INSERT INTO cliente (nombre, apellido, correo, telefono, direccion) VALUES ('$nombre', '$apellido', '$correo', '$telefono', '$direccion')");
    } else {
        $id_cli = intval($_POST['id_cliente']);
        $conn->query("UPDATE cliente SET nombre='$nombre', apellido='$apellido', correo='$correo', telefono='$telefono', direccion='$direccion' WHERE id_cliente=$id_cli");
    }
    header("Location: clientes.php"); exit;
}

$editando = false;
$cli_edit = ['id_cliente'=>'', 'nombre'=>'', 'apellido'=>'', 'correo'=>'', 'telefono'=>'', 'direccion'=>''];
if (isset($_GET['editar'])) {
    $id_edit = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM cliente WHERE id_cliente = $id_edit");
    if ($res->num_rows > 0) { $cli_edit = $res->fetch_assoc(); $editando = true; }
}

require 'header.php';
echo $mensaje;
?>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <h4 class="section-title fs-5"><?php echo $editando ? 'Editar Cliente' : 'Nuevo Cliente'; ?></h4>
            <form method="POST">
                <input type="hidden" name="accion" value="<?php echo $editando ? 'actualizar' : 'crear'; ?>">
                <input type="hidden" name="id_cliente" value="<?php echo $cli_edit['id_cliente']; ?>">
                
                <div class="row">
                    <div class="col-6 mb-3"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" required value="<?php echo $cli_edit['nombre']; ?>"></div>
                    <div class="col-6 mb-3"><label class="form-label">Apellido</label><input type="text" name="apellido" class="form-control" required value="<?php echo $cli_edit['apellido']; ?>"></div>
                </div>
                <div class="mb-3"><label class="form-label">Correo</label><input type="email" name="correo" class="form-control" required value="<?php echo $cli_edit['correo']; ?>"></div>
                <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" required value="<?php echo $cli_edit['telefono']; ?>"></div>
                <div class="mb-3"><label class="form-label">Dirección</label><input type="text" name="direccion" class="form-control" required value="<?php echo $cli_edit['direccion']; ?>"></div>
                <button type="submit" class="btn btn-primary w-100"><?php echo $editando ? 'Guardar Cambios' : 'Registrar Cliente'; ?></button>
                <?php if($editando): ?><a href="clientes.php" class="btn btn-light w-100 mt-2 border">Cancelar</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <h4 class="section-title fs-5">Directorio de Clientes</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Cliente</th><th>Contacto</th><th>Dirección</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM cliente ORDER BY id_cliente DESC");
                        while($row = $res->fetch_assoc()) {
                            echo "<tr>
                                    <td><strong>{$row['nombre']} {$row['apellido']}</strong><br><small class='text-muted'>{$row['correo']}</small></td>
                                    <td>{$row['telefono']}</td>
                                    <td>{$row['direccion']}</td>
                                    <td>
                                        <a href='clientes.php?editar={$row['id_cliente']}' class='btn btn-sm btn-light border'>Editar</a>
                                        <a href='clientes.php?eliminar={$row['id_cliente']}' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Seguro que deseas eliminar?\")'>Borrar</a>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require 'footer.php'; ?>