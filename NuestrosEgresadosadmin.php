<?php
// MVC: Usar Controller y Model existentes
include 'config/conexion.php';
require_once 'models/Egresado.php';
require_once 'controllers/EgresadoController.php';

// Habilitar manejo de errores (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión para mensajes flash
session_start();

// Función para obtener mensajes flash
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

// Inicializar aplicación usando MVC
try {
    $egresadoController = new EgresadoController();
    
    // Obtener parámetros
    $search = trim($_GET['search'] ?? '');
    $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
    $por_pagina = 20;
    $offset = ($pagina - 1) * $por_pagina;

    // Procesar búsqueda usando Controller
    if (!empty($search)) {
        $egresados = $egresadoController->searchEgresados($search, $por_pagina, $offset);
        $total_egresados = $egresadoController->countSearchEgresados($search);
    } else {
        $egresados = $egresadoController->getAllEgresados($por_pagina, $offset);
        $total_egresados = $egresadoController->countEgresados();
    }

    // Validar que no sea null
    if ($egresados === null) {
        $egresados = [];
    }
    if ($total_egresados === null) {
        $total_egresados = 0;
    }

    $total_paginas = $total_egresados > 0 ? ceil($total_egresados / $por_pagina) : 1;
    $flashMessage = getFlashMessage();
    
} catch (Exception $e) {
    error_log("Error inicializando aplicación: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    // Mostrar error más detallado en desarrollo
    if (ini_get('display_errors')) {
        die("Error al inicializar la aplicación: " . $e->getMessage() . "<br>Archivo: " . $e->getFile() . "<br>Línea: " . $e->getLine());
    } else {
    die("Error al inicializar la aplicación. Por favor, intente más tarde.");
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Egresados - IEST La Recoleta</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; line-height: 1.6; }
        .container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; }
        
        /* Cabecera Académica Elegante */
        .header { 
            background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%); 
            color: white; 
            padding: 30px 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-bottom: 4px solid #ffc107;
        }
        .header-top { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 0;
        }
        .logo { 
            display: flex; 
            align-items: center; 
            gap: 25px;
        }
        .logo img { 
            height: 85px; 
            width: auto;
            max-width: 120px;
            filter: drop-shadow(0 3px 6px rgba(0,0,0,0.3));
            object-fit: contain;
        }
        .logo-text { 
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .logo-text h1 { 
            font-size: 32px; 
            margin: 0; 
            font-weight: 800;
            color: #ffc107;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            letter-spacing: 1px;
            line-height: 1.2;
        }
        .logo-text p { 
            font-size: 16px; 
            opacity: 0.95; 
            margin: 5px 0 0 0; 
            font-weight: 400;
            color: white;
            letter-spacing: 0.5px;
        }
        .fecha { 
            text-align: right; 
            font-size: 14px; 
            background: rgba(255,255,255,0.15);
            padding: 10px 15px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }
        
        /* Navegación Académica */
        .nav { 
            background: linear-gradient(135deg, #263238, #37474f); 
            padding: 12px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-links { display: flex; gap: 12px; flex-wrap: wrap; }
        .nav-links a { 
            color: white; 
            text-decoration: none; 
            padding: 10px 18px; 
            border-radius: 6px; 
            transition: all 0.3s;
            font-weight: 500;
            font-size: 14px;
        }
        .nav-links a:hover { 
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        /* Contenido principal */
        .main-content { padding: 35px 40px; background: #fafafa; }
        h1 { 
            color: #1a237e; 
            margin-bottom: 30px; 
            text-align: center; 
            padding-bottom: 20px; 
            border-bottom: 3px solid #ffc107;
            font-size: 32px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Barra de búsqueda elegante */
        .search-box { 
            background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%); 
            padding: 30px; 
            border-radius: 12px; 
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
        }
        .search-form { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
        .search-box input { 
            flex: 1; 
            min-width: 250px; 
            padding: 14px 18px; 
            border: 2px solid #e0e0e0; 
            border-radius: 8px; 
            font-size: 15px;
            transition: all 0.3s;
        }
        .search-box input:focus { 
            border-color: #3949ab; 
            box-shadow: 0 0 0 3px rgba(57, 73, 171, 0.1); 
            outline: none;
        }
        
        /* Botones elegantes */
        .btn { 
            padding: 12px 20px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            text-decoration: none; 
            display: inline-block; 
            font-weight: 600; 
            transition: all 0.3s ease; 
            font-size: 14px; 
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .btn-primary { background: linear-gradient(135deg, #3949ab, #5c6bc0); color: white; }
        .btn-primary:hover { background: linear-gradient(135deg, #283593, #3949ab); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(57, 73, 171, 0.4); }
        .btn-success { background: linear-gradient(135deg, #2e7d32, #43a047); color: white; }
        .btn-success:hover { background: linear-gradient(135deg, #1b5e20, #2e7d32); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(46, 125, 50, 0.4); }
        .btn-warning { background: linear-gradient(135deg, #f57c00, #fb8c00); color: white; }
        .btn-warning:hover { background: linear-gradient(135deg, #e65100, #f57c00); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(245, 124, 0, 0.4); }
        .btn-info { background: linear-gradient(135deg, #0277bd, #0288d1); color: white; }
        .btn-info:hover { background: linear-gradient(135deg, #01579b, #0277bd); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(2, 119, 189, 0.4); }
        .btn-secondary { background: linear-gradient(135deg, #546e7a, #607d8b); color: white; }
        .btn-secondary:hover { background: linear-gradient(135deg, #37474f, #546e7a); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(84, 110, 122, 0.4); }
        .btn-danger { background: linear-gradient(135deg, #c62828, #d32f2f); color: white; }
        .btn-danger:hover { background: linear-gradient(135deg, #b71c1c, #c62828); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(198, 40, 40, 0.4); }
        
        /* Tabla Académica Elegante */
        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
            margin-top: 25px; 
            background: white; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            border: 1px solid #e0e0e0;
        }
        thead {
            background: linear-gradient(135deg, #1a237e, #283593);
        }
        th { 
            padding: 18px 16px; 
            text-align: left; 
            color: white; 
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: sticky; 
            top: 0;
            z-index: 10;
        }
        th:first-child { border-top-left-radius: 12px; }
        th:last-child { border-top-right-radius: 12px; }
        td { 
            padding: 16px; 
            text-align: left; 
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }
        tbody tr {
            transition: all 0.2s ease;
        }
        tbody tr:hover { 
            background: linear-gradient(90deg, #f8f9ff, #ffffff);
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        tbody tr:last-child td:first-child { border-bottom-left-radius: 12px; }
        tbody tr:last-child td:last-child { border-bottom-right-radius: 12px; }
        
        /* Footer Académico */
        .footer { 
            background: linear-gradient(135deg, #263238, #37474f); 
            color: white; 
            padding: 30px 40px; 
            margin-top: 40px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
        }
        .footer-content { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap; 
            gap: 20px; 
        }
        .footer-info { 
            flex: 1; 
        }
        .footer-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .footer-info strong {
            font-size: 18px;
            color: #ffc107;
        }
        .footer-links { 
            display: flex; 
            gap: 20px; 
            flex-wrap: wrap; 
        }
        .footer-links a { 
            color: #e0e0e0; 
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 500;
        }
        .footer-links a:hover { 
            color: #ffc107;
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }
        
        /* Estilos para impresión */
        @media print {
            .no-print { display: none !important; }
            body { background: white; padding: 0; margin: 0; }
            .container { box-shadow: none; margin: 0; max-width: 100%; }
            .header, .nav, .footer { display: none; }
            .main-content { padding: 0; }
            table { box-shadow: none; border: 1px solid #ddd; }
            th { background: #f8f9fa !important; color: #000 !important; border: 1px solid #ddd; }
            td { border: 1px solid #ddd; }
            .btn { display: none !important; }
        }
        
        @media (max-width: 768px) {
            .search-box { padding: 15px; }
            .search-form { flex-direction: column; align-items: stretch; }
            .search-box input { min-width: auto; }
            .header-top { flex-direction: column; gap: 10px; text-align: center; }
            .fecha { text-align: center; }
            .footer-content { flex-direction: column; gap: 15px; text-align: center; }
            .nav-links { justify-content: center; }
            table { font-size: 14px; }
            th, td { padding: 8px; }
        }
        
        /* Alertas elegantes */
        .alert { 
            padding: 18px 25px; 
            margin: 25px 0; 
            border-radius: 10px; 
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-left: 5px solid;
        }
        .alert-success { 
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9); 
            color: #1b5e20; 
            border-left-color: #4caf50;
        }
        .alert-error { 
            background: linear-gradient(135deg, #ffebee, #fce4ec); 
            color: #b71c1c; 
            border-left-color: #f44336;
        }
        .alert-warning { 
            background: linear-gradient(135deg, #fff8e1, #fffde7); 
            color: #e65100; 
            border-left-color: #ff9800;
        }
        /* Paginación elegante */
        .pagination { 
            display: flex; 
            justify-content: center; 
            margin: 30px 0; 
            gap: 8px; 
            flex-wrap: wrap;
            align-items: center;
        }
        .pagination .btn { 
            padding: 10px 16px;
            min-width: 45px;
            font-weight: 600;
        }
        .pagination-info { 
            text-align: center; 
            margin: 20px 0; 
            color: #546e7a;
            font-size: 15px;
            font-weight: 500;
            padding: 12px;
            background: #f5f5f5;
            border-radius: 8px;
        }
        /* Estadísticas Académicas */
        .stats-box { 
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%); 
            padding: 25px; 
            border-radius: 12px; 
            margin: 20px 0; 
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 2px solid #e3f2fd;
        }
        .stats-box h3 { 
            color: #1a237e; 
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .stats-box p {
            font-size: 16px;
            color: #37474f;
            margin: 8px 0;
        }
        .stats-box strong {
            color: #3949ab;
            font-size: 18px;
            font-weight: 700;
        }
        .action-buttons { 
            display: flex; 
            gap: 8px; 
            flex-wrap: wrap;
            justify-content: center;
        }
        .action-buttons .btn {
            padding: 8px 16px;
            font-size: 13px;
        }
        
        /* Mejoras en la información del egresado */
        td strong {
            color: #1a237e;
            font-size: 15px;
            font-weight: 600;
        }
        td small {
            display: block;
            color: #546e7a;
            font-size: 12px;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabecera -->
        <header class="header no-print">
            <div class="header-top">
                <div class="logo">
                    <img src="logo.png" alt="Logo IEST La Recoleta" onerror="this.style.display='none'">
                    <div class="logo-text">
                        <h1>IEST "LA RECOLETA"</h1>
                        <p>Sistema de Gestión de Egresados</p>
                    </div>
                </div>
                <div class="fecha">
                    <p><?php echo date('d/m/Y'); ?></p>
                    <p><?php echo date('H:i:s'); ?></p>
                </div>
            </div>
        </header>

        <!-- Navegación -->
        <nav class="nav no-print">
            <div class="nav-links">
                <a href="?">Inicio</a>
                <a href="NuestrosEgresados.php">Nuevo Egresado</a>
                <a href="https://www.iestlarecoleta.edu.pe" target="_blank" rel="noopener noreferrer">Sitio Web</a>
                <a href="#" onclick="imprimirLista()">Imprimir</a>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="main-content">
            <h1>REGISTRO DE EGRESADOS</h1>
            
            <?php if ($flashMessage): ?>
                <div class="alert alert-<?php echo $flashMessage['type']; ?>">
                    <?php echo htmlspecialchars($flashMessage['message']); ?>
                </div>
            <?php endif; ?>

            <!-- Estadísticas -->
            <div class="stats-box no-print">
                <h3>ESTADÍSTICAS DEL SISTEMA</h3>
                <p>Total de egresados registrados: <strong><?php echo number_format($total_egresados, 0, ',', '.'); ?></strong></p>
                <?php if (!empty($search)): ?>
                    <p>Resultados de búsqueda para "<?php echo htmlspecialchars($search); ?>": <strong><?php echo count($egresados); ?> encontrados</strong></p>
                <?php endif; ?>
            </div>

            <!-- Barra de búsqueda -->
            <div class="search-box no-print">
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Buscar por DNI, nombres o apellidos..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <?php if (!empty($search)): ?>
                        <a href="?" class="btn btn-secondary">Mostrar Todos</a>
                    <?php endif; ?>
                    <a href="NuestrosEgresados.php" class="btn btn-success">Nuevo Egresado</a>
                    <button type="button" onclick="imprimirLista()" class="btn btn-info">Imprimir Lista</button>
                </form>
            </div>

            <!-- Lista de egresados -->
            <?php if ($total_egresados > 0): ?>
                <div class="pagination-info no-print">
                    Mostrando <?php echo count($egresados); ?> de <?php echo $total_egresados; ?> egresados
                    <?php if ($pagina > 1): ?> - Página <?php echo $pagina; ?> de <?php echo $total_paginas; ?><?php endif; ?>
                </div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>Nombres y Apellidos</th>
                        <th>Programa</th>
                        <th>Año Ingreso</th>
                        <th>Año Egreso</th>
                        <th>Estado</th>
                        <th class="no-print">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($egresados)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #6c757d;">
                                <?php if (!empty($search)): ?>
                                    🔍 No se encontraron egresados para "<?php echo htmlspecialchars($search); ?>"
                                <?php else: ?>
                                    📝 No hay egresados registrados. <a href="formulario_egresado.php" class="btn btn-success">Agregar el primero</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($egresados as $egresado): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($egresado['dni'] ?? ''); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars(($egresado['nombres'] ?? '') . ' ' . ($egresado['apellidos'] ?? '')); ?></strong>
                                    <?php if (!empty($egresado['email'])): ?>
                                        <br><small style="color: #546e7a;"><?php echo htmlspecialchars($egresado['email']); ?></small>
                                    <?php endif; ?>
                                    <?php if (!empty($egresado['telefono'])): ?>
                                        <br><small style="color: #546e7a;"><?php echo htmlspecialchars($egresado['telefono']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($egresado['nombre_programa'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($egresado['año_ingreso'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($egresado['año_egreso'] ?? ''); ?></td>
                                <td>
                                    <?php
                                    $estado = $egresado['estado_actual'] ?? 'DESCONOCIDO';
                                    $estados = [
                                        'TITULADO' => ['color' => '#155724', 'bg' => '#d4edda'],
                                        'CERTIFICADO' => ['color' => '#0c5460', 'bg' => '#d1ecf1'],
                                        'EN PROCESO' => ['color' => '#856404', 'bg' => '#fff3cd'],
                                        'EGRESADO' => ['color' => '#383d41', 'bg' => '#e2e3e5']
                                    ];
                                    $estadoConfig = $estados[$estado] ?? $estados['EGRESADO'];
                                    ?>
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; 
                                        background: <?php echo $estadoConfig['bg']; ?>; color: <?php echo $estadoConfig['color']; ?>">
                                        <?php echo $estado; ?>
                                    </span>
                                </td>
                                <td class="no-print">
                                    <div class="action-buttons">
                                        <a href="NuestrosEgresados.php?action=edit&id=<?php echo $egresado['id_egresado']; ?>" 
                                           class="btn btn-warning">Editar</a>
                                        <a href="imprimir_egresado.php?id=<?php echo $egresado['id_egresado']; ?>" 
                                           target="_blank" class="btn btn-info">Ver Ficha</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <?php if ($total_paginas > 1): ?>
            <div class="pagination no-print">
                <?php if ($pagina > 1): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => 1])); ?>" class="btn btn-secondary">« Primera</a>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina - 1])); ?>" class="btn btn-secondary">‹ Anterior</a>
                <?php endif; ?>

                <?php
                $inicio = max(1, $pagina - 2);
                $fin = min($total_paginas, $pagina + 2);
                
                for ($i = $inicio; $i <= $fin; $i++):
                ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $i])); ?>" 
                       class="btn <?php echo $i == $pagina ? 'btn-primary' : 'btn-secondary'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($pagina < $total_paginas): ?>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina + 1])); ?>" class="btn btn-secondary">Siguiente ›</a>
                    <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $total_paginas])); ?>" class="btn btn-secondary">Última »</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </main>

        <!-- Footer -->
        <footer class="footer no-print">
            <div class="footer-content">
                <div class="footer-info">
                    <p><strong>IEST "LA RECOLETA"</strong></p>
                    <p>Sistema de Gestión de Egresados &copy; <?php echo date('Y'); ?></p>
                </div>
                <div class="footer-links">
                    <a href="https://www.iestlarecoleta.edu.pe" target="_blank" rel="noopener noreferrer">Sitio Web</a>
                    <a href="mailto:informes@iestlarecoleta.edu.pe">Contacto</a>
                    <a href="#" onclick="imprimirLista()">Imprimir</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Función para imprimir
        function imprimirLista() {
            window.print();
        }

        // Auto-eliminar mensajes después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>