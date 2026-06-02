<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopOnline Huila - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estética Minimalista y Premium */
        body { 
            background-color: #f5f5f7; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; 
            color: #1d1d1f;
        }
        /* Navbar con efecto Glassmorphism */
        .navbar { 
            background: rgba(255, 255, 255, 0.7) !important; 
            backdrop-filter: saturate(180%) blur(20px); 
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.05); 
        }
        .navbar-brand { font-weight: 600; color: #1d1d1f !important; letter-spacing: -0.5px; }
        .nav-link { color: #515154 !important; font-weight: 500; font-size: 0.95rem; }
        .nav-link:hover, .nav-link.active { color: #0071e3 !important; }
        /* Tarjetas limpias */
        .card { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04); 
            background: #ffffff; 
            padding: 24px;
        }
        .section-title { font-weight: 600; letter-spacing: -0.5px; margin-bottom: 20px; color: #1d1d1f; }
        /* Botones estilo minimalista */
        .btn { border-radius: 8px; font-weight: 500; padding: 10px 16px; transition: all 0.2s ease; }
        .btn-primary { background-color: #0071e3; border-color: #0071e3; }
        .btn-primary:hover { background-color: #0077ed; transform: scale(1.02); }
        .btn-danger { background-color: #e30000; border: none; }
        .btn-sm { padding: 6px 12px; font-size: 0.85rem; border-radius: 6px; }
        /* Tablas */
        .table { border-collapse: separate; border-spacing: 0 8px; margin-top: -8px; }
        .table thead th { border-bottom: none; color: #86868b; font-weight: 500; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .table tbody tr { background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.02); border-radius: 8px; transition: transform 0.1s; }
        .table tbody tr:hover { transform: scale(1.005); box-shadow: 0 4px 12px rgba(0,0,0,0.05); z-index: 1; position: relative; }
        .table td { border-top: 1px solid #f5f5f7; border-bottom: 1px solid #f5f5f7; vertical-align: middle; }
        .table td:first-child { border-left: 1px solid #f5f5f7; border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
        .table td:last-child { border-right: 1px solid #f5f5f7; border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
        /* Inputs limpios */
        .form-control, .form-select { border-radius: 10px; border: 1px solid #d2d2d7; padding: 12px; background-color: #fafafa; }
        .form-control:focus, .form-select:focus { border-color: #0071e3; box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.1); background-color: #fff; }
        .form-label { font-size: 0.9rem; font-weight: 500; color: #1d1d1f; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg sticky-top mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">ShopOnline</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto gap-2">
                <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="crear_pedido.php">Nuevo Pedido</a></li>
                <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="empleados.php">Empleados</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mb-5">