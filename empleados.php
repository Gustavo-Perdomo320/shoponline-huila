<?php
// empleados.php
require 'db.php';
$mensaje = '';

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    try {
        $conn->query("DELETE FROM empleado WHERE id_empleado = $id");
        header("Location: empleados.php"); exit;
    } catch (Exception $e) {
        $mensaje = "<div class='alert alert-danger'>Error: El empleado está asignado a despachos existentes.</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $cargo = $conn->real_escape_string($_POST['cargo']);
    $salario = floatval($_POST['salario']);
    $fecha = $_POST['fecha_ingreso'];
    
    if ($_POST['accion'] == 'crear') {
        $conn->query("INSERT INTO empleado (nombre, apellido, cargo, salario, fecha_ingreso) VALUES ('$nombre', '$apellido', '$cargo', $salario, '$fecha')");
    } else {
        $id_emp = intval($_POST['id_empleado']);
        $conn->query("UPDATE empleado SET nombre='$nombre', apellido='$apellido', cargo='$cargo', salario=$salario, fecha_ingreso='$fecha' WHERE id_empleado=$id_emp");
    }
    header("Location: empleados.php"); exit;
}

$editando = false;
$emp_edit = ['id_empleado'=>'', 'nombre'=>'', 'apellido'=>'', 'cargo'=>'', 'salario'=>'', 'fecha_ingreso'=>''];
if (isset($_GET['editar'])) {
    $id_edit = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM empleado WHERE id_empleado = $id_edit");
    if ($res->num_rows > 0) { $emp_edit = $res->fetch_assoc(); $editando = true; }
}

require 'header.php';
echo $mensaje;
?>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <h4 class="section-title fs-5"><?php echo $editando ? 'Editar Empleado' : 'Nuevo Empleado'; ?></h4>
            <form method="POST">
                <input type="hidden" name="accion" value="<?php echo $editando ? 'actualizar' : 'crear'; ?>">
                <input type="hidden" name="id_empleado" value="<?php echo $emp_edit['id_empleado']; ?>">
                
                <div class="row">
                    <div class="col-6 mb-3"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" required value="<?php echo $emp_edit['nombre']; ?>"></div>
                    <div class="col-6 mb-3"><label class="form-label">Apellido</label><input type="text" name="apellido" class="form-control" required value="<?php echo $emp_edit['apellido']; ?>"></div>
                </div>
                <div class="mb-3"><label class="form-label">Cargo</label><input type="text" name="cargo" class="form-control" required value="<?php echo $emp_edit['cargo']; ?>"></div>
                <div class="mb-3"><label class="form-label">Salario Base</label><input type="number" step="0.01" name="salario" class="form-control" required value="<?php echo $emp_edit['salario']; ?>"></div>
                <div class="mb-3"><label class="form-label">Fecha de Ingreso</label><input type="date" name="fecha_ingreso" class="form-control" required value="<?php echo $emp_edit['fecha_ingreso']; ?>"></div>
                <button type="submit" class="btn btn-primary w-100"><?php echo $editando ? 'Guardar Cambios' : 'Registrar Empleado'; ?></button>
                <?php if($editando): ?><a href="empleados.php" class="btn btn-light w-100 mt-2 border">Cancelar</a><?php endif; ?>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <h4 class="section-title fs-5">Nómina de Personal</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Nombre</th><th>Cargo</th><th>Salario</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM empleado ORDER BY id_empleado DESC");
                        while($row = $res->fetch_assoc()) {
                            echo "<tr>
                                    <td><strong>{$row['nombre']} {$row['apellido']}</strong></td>
                                    <td class='text-muted'>{$row['cargo']}</td>
                                    <td>$" . number_format($row['salario'], 2) . "</td>
                                    <td>
                                        <a href='empleados.php?editar={$row['id_empleado']}' class='btn btn-sm btn-light border'>Editar</a>
                                        <a href='empleados.php?eliminar={$row['id_empleado']}' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Eliminar empleado?\")'>Borrar</a>
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