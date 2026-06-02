<?php
// productos.php
require 'db.php';

$mensaje = '';

// Procesar Eliminar
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    try {
        $conn->query("DELETE FROM producto WHERE id_producto = $id");
        header("Location: productos.php"); exit;
    } catch (Exception $e) {
        $mensaje = "<div class='alert alert-danger'>No se puede eliminar este producto porque está asociado a un pedido existente.</div>";
    }
}

// Procesar Crear o Actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cat = intval($_POST['id_categoria']);
    $nombre = $conn->real_escape_string($_POST['nombre_producto']);
    $precio = floatval($_POST['precio']);
    $stock  = intval($_POST['stock']);
    
    if ($_POST['accion'] == 'crear') {
        $conn->query("INSERT INTO producto (id_categoria, nombre_producto, precio, stock) VALUES ($id_cat, '$nombre', $precio, $stock)");
    } else if ($_POST['accion'] == 'actualizar') {
        $id_prod = intval($_POST['id_producto']);
        $conn->query("UPDATE producto SET id_categoria=$id_cat, nombre_producto='$nombre', precio=$precio, stock=$stock WHERE id_producto=$id_prod");
    }
    header("Location: productos.php"); exit;
}

// Lógica para cargar datos si se va a editar
$editando = false;
$producto_edit = ['id_producto'=>'', 'id_categoria'=>'', 'nombre_producto'=>'', 'precio'=>'', 'stock'=>''];
if (isset($_GET['editar'])) {
    $id_edit = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM producto WHERE id_producto = $id_edit");
    if ($res->num_rows > 0) {
        $producto_edit = $res->fetch_assoc();
        $editando = true;
    }
}

require 'header.php';
echo $mensaje;
?>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <h4 class="section-title fs-5"><?php echo $editando ? 'Editar Producto' : 'Nuevo Producto'; ?></h4>
            <form method="POST">
                <input type="hidden" name="accion" value="<?php echo $editando ? 'actualizar' : 'crear'; ?>">
                <input type="hidden" name="id_producto" value="<?php echo $producto_edit['id_producto']; ?>">
                
                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <select name="id_categoria" class="form-select" required>
                        <?php
                        $conn->query("INSERT IGNORE INTO categoria (id_categoria, nombre_categoria) VALUES (1, 'General')");
                        $cats = $conn->query("SELECT * FROM categoria");
                        while($c = $cats->fetch_assoc()) {
                            $sel = ($producto_edit['id_categoria'] == $c['id_categoria']) ? 'selected' : '';
                            echo "<option value='{$c['id_categoria']}' $sel>{$c['nombre_categoria']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre_producto" class="form-control" required value="<?php echo $producto_edit['nombre_producto']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio</label>
                    <input type="number" step="0.01" name="precio" class="form-control" required value="<?php echo $producto_edit['precio']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" required value="<?php echo $producto_edit['stock']; ?>">
                </div>
                <button type="submit" class="btn btn-primary w-100"><?php echo $editando ? 'Actualizar Cambios' : 'Guardar Producto'; ?></button>
                <?php if($editando): ?>
                    <a href="productos.php" class="btn btn-light w-100 mt-2 border">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <h4 class="section-title fs-5">Inventario</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php
                        $prod_res = $conn->query("SELECT * FROM producto ORDER BY id_producto DESC");
                        while($p = $prod_res->fetch_assoc()) {
                            echo "<tr>
                                    <td class='text-muted'>#{$p['id_producto']}</td>
                                    <td><strong>{$p['nombre_producto']}</strong></td>
                                    <td>$" . number_format($p['precio'], 2) . "</td>
                                    <td>{$p['stock']} u.</td>
                                    <td>
                                        <a href='productos.php?editar={$p['id_producto']}' class='btn btn-sm btn-light border'>Editar</a>
                                        <a href='productos.php?eliminar={$p['id_producto']}' class='btn btn-sm btn-danger text-white' onclick='return confirm(\"¿Eliminar producto?\")'>Borrar</a>
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