<?php
/**
 * Entry Point - Vista de impresión de egresado
 */

require_once '../config/conexion.php';
require_once '../controllers/EgresadoController.php';
require_once '../config/helpers.php';

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No se proporcionó ID de egresado");
}

$id_egresado = intval($_GET['id']);

// Usar Controller para obtener datos
try {
    $controller = new EgresadoController();
    $egresado = $controller->handlePrint($id_egresado);
    
    // Obtener información del estado
    $estado_info = obtenerEstado($egresado['estado_actual'] ?? 'EGRESADO');
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Egresado - <?php echo htmlspecialchars($egresado['nombres'] . ' ' . $egresado['apellidos']); ?></title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/print.css">
</head>
<body>
    <div class="container">
        <!-- Cabecera institucional -->
        <div class="header">
            <div class="institucion">IEST "LA RECOLETA"</div>
            <div class="sistema">Sistema de Gestión de Egresados</div>
            <div class="titulo-documento">FICHA DE EGRESADO</div>
        </div>
        
        <!-- Información del egresado -->
        <div class="info-container">
            <!-- Estado destacado -->
            <div class="estado-container">
                <div class="estado-titulo">ESTADO ACTUAL</div>
                <div class="estado-valor"><?php echo $estado_info['texto']; ?></div>
            </div>
            
            <!-- Información personal -->
            <div class="seccion">
                <div class="seccion-titulo">INFORMACIÓN PERSONAL</div>
                <div class="grid-datos">
                    <div class="campo">
                        <div class="etiqueta">DNI</div>
                        <div class="valor"><?php echo htmlspecialchars($egresado['dni']); ?></div>
                    </div>
                    <div class="campo">
                        <div class="etiqueta">Nombres Completos</div>
                        <div class="valor"><?php echo htmlspecialchars($egresado['nombres']); ?></div>
                    </div>
                    <div class="campo">
                        <div class="etiqueta">Apellidos Completos</div>
                        <div class="valor"><?php echo htmlspecialchars($egresado['apellidos']); ?></div>
                    </div>
                    <div class="campo">
                        <div class="etiqueta">Fecha de Nacimiento</div>
                        <div class="valor"><?php echo formatearFecha($egresado['fecha_nacimiento']); ?></div>
                    </div>
                    <div class="campo">
                        <div class="etiqueta">Edad</div>
                        <div class="valor"><?php echo calcularEdad($egresado['fecha_nacimiento']); ?> años</div>
                    </div>
                    <div class="campo">
                        <div class="etiqueta">Sexo</div>
                        <div class="valor"><?php echo htmlspecialchars($egresado['sexo'] ?? 'No especificado'); ?></div>
                    </div>
                    <div class="campo">
                        <div class="etiqueta">Estado Civil</div>
                        <div class="valor"><?php echo htmlspecialchars($egresado['estado_civil'] ?? 'No especificado'); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Información de contacto -->
            <div class="seccion">
                <div class="seccion-titulo">INFORMACIÓN DE CONTACTO</div>
                <div class="grid-datos">
                    <div class="campo">
                        <div class="etiqueta">Teléfono</div>
                        <div class="valor <?php echo empty($egresado['telefono']) ? 'valor-vacio' : ''; ?>">
                            <?php echo !empty($egresado['telefono']) ? htmlspecialchars($egresado['telefono']) : 'No registrado'; ?>
                        </div>
                    </div>
                    <div class="campo">
                        <div class="etiqueta">Email</div>
                        <div class="valor <?php echo empty($egresado['email']) ? 'valor-vacio' : ''; ?>">
                            <?php echo !empty($egresado['email']) ? htmlspecialchars($egresado['email']) : 'No registrado'; ?>
                        </div>
                    </div>
                    <div class="campo" style="grid-column: 1 / -1;">
                        <div class="etiqueta">Dirección</div>
                        <div class="valor <?php echo empty($egresado['direccion']) ? 'valor-vacio' : ''; ?>">
                            <?php echo !empty($egresado['direccion']) ? htmlspecialchars($egresado['direccion']) : 'No registrada'; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Información académica -->
            <div class="seccion">
                <div class="seccion-titulo">INFORMACIÓN ACADÉMICA</div>
                <div class="grid-datos">
                    <div class="campo">
                        <div class="etiqueta">Programa de Estudios</div>
                        <div class="valor"><?php echo htmlspecialchars($egresado['nombre_programa'] ?? 'No especificado'); ?></div>
                    </div>
                    <div class="campo">
                        <div class="etiqueta">Año de Ingreso</div>
                        <div class="valor"><?php echo htmlspecialchars($egresado['año_ingreso']); ?></div>
                    </div>
                    <div class="campo">
                        <div class="etiqueta">Año de Egreso</div>
                        <div class="valor"><?php echo htmlspecialchars($egresado['año_egreso']); ?></div>
                    </div>
                    <div class="campo">
                        <div class="etiqueta">Duración de Estudios</div>
                        <div class="valor">
                            <?php 
                            $duracion = ($egresado['año_egreso'] ?? 0) - ($egresado['año_ingreso'] ?? 0);
                            echo $duracion > 0 ? $duracion . ' año' . ($duracion != 1 ? 's' : '') : 'No especificada';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Código QR (placeholder) -->
            <div class="codigo-qr">
                <div class="etiqueta" style="text-align: center; margin-bottom: 10px;">CÓDIGO DE VERIFICACIÓN</div>
                <div class="qr-placeholder">
                    Código QR<br>DNI: <?php echo htmlspecialchars($egresado['dni']); ?>
                </div>
                <div style="font-size: 12px; color: #6c757d; margin-top: 10px;">
                    ID: EG<?php echo str_pad($egresado['id_egresado'], 6, '0', STR_PAD_LEFT); ?>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div style="margin-bottom: 10px;">
                <strong>IEST "LA RECOLETA"</strong><br>
                Sistema de Gestión de Egresados
            </div>
            <div style="font-size: 12px; opacity: 0.8;">
                Documento generado el: <?php echo date('d/m/Y H:i:s'); ?><br>
                Este documento es una constancia oficial del registro en el sistema
            </div>
        </div>
        
        <!-- Botones de acción (no se imprimen) -->
        <div class="acciones no-print">
            <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimir Ficha</button>
            <button onclick="window.close()" class="btn btn-success">❌ Cerrar Ventana</button>
            <a href="NuestrosEgresados.php?action=edit&id=<?php echo $egresado['id_egresado']; ?>" class="btn btn-primary">✏️ Editar Egresado</a>
        </div>
    </div>

    <script src="js/print.js"></script>
</body>
</html>
