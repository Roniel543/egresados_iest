<?php
// MVC: Usar Controller y Model existentes
include 'config/conexion.php';
require_once 'models/Egresado.php';
require_once 'controllers/EgresadoController.php';
require_once 'config/helpers.php';

// Habilitar manejo de errores (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión para mensajes flash
session_start();

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
    <link rel="stylesheet" href="css/main.css">
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

            <div class="table-wrapper">
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
            </div>

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

    <script src="js/list.js"></script>
</body>
</html>