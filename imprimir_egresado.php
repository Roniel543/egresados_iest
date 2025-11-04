<?php
// MVC: Usar Model existente
require_once 'config/conexion.php';
require_once 'models/Egresado.php';
require_once 'controllers/EgresadoController.php';

// Habilitar manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si se proporcionó un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No se proporcionó ID de egresado");
}

$id_egresado = intval($_GET['id']);

if ($id_egresado <= 0) {
    die("Error: ID de egresado inválido");
}

try {
    // Usar Controller para obtener egresado
    $egresadoController = new EgresadoController();
    $egresado = $egresadoController->getEgresadoById($id_egresado);
    
    if (!$egresado) {
        die("Error: Egresado no encontrado con ID: " . $id_egresado);
    }
} catch (Exception $e) {
    die("Error al obtener datos del egresado: " . $e->getMessage());
}

// Función para formatear fecha
function formatearFecha($fecha) {
    if (empty($fecha) || $fecha == '0000-00-00') {
        return 'No especificada';
    }
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha);
    return $fecha_obj ? $fecha_obj->format('d/m/Y') : 'Fecha inválida';
}

// Función para obtener la edad
function calcularEdad($fecha_nacimiento) {
    if (empty($fecha_nacimiento) || $fecha_nacimiento == '0000-00-00') {
        return 'No especificada';
    }
    
    $hoy = new DateTime();
    $fecha_nac = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
    
    if (!$fecha_nac) {
        return 'Fecha inválida';
    }
    
    $edad = $hoy->diff($fecha_nac);
    return $edad->y;
}

// Determinar el estado con color
function obtenerEstado($estado) {
    switch($estado) {
        case 'TITULADO':
            return ['texto' => 'TITULADO', 'color' => '#28a745', 'bgcolor' => '#d4edda'];
        case 'CERTIFICADO':
            return ['texto' => 'CERTIFICADO', 'color' => '#17a2b8', 'bgcolor' => '#d1ecf1'];
        case 'EN PROCESO':
            return ['texto' => 'EN PROCESO', 'color' => '#ffc107', 'bgcolor' => '#fff3cd'];
        default:
            return ['texto' => 'EGRESADO', 'color' => '#6c757d', 'bgcolor' => '#e2e3e5'];
    }
}

$estado_info = obtenerEstado($egresado['estado_actual'] ?? 'EGRESADO');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Egresado - <?php echo htmlspecialchars($egresado['nombres'] . ' ' . $egresado['apellidos']); ?></title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: 'Arial', sans-serif; 
            background: #f8f9fa; 
            color: #333;
            line-height: 1.4;
        }
        
        .container { 
            max-width: 800px; 
            margin: 20px auto; 
            background: white; 
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        /* Cabecera institucional */
        .header { 
            background: linear-gradient(135deg, #2c3e50, #3498db); 
            color: white; 
            padding: 25px 30px;
            text-align: center;
            border-bottom: 5px solid #e74c3c;
        }
        
        .institucion {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .sistema {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 15px;
        }
        
        .titulo-documento {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            padding: 10px;
            background: rgba(255,255,255,0.1);
            border-radius: 5px;
            display: inline-block;
        }
        
        /* Información del egresado */
        .info-container {
            padding: 30px;
        }
        
        .seccion {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .seccion:last-child {
            border-bottom: none;
        }
        
        .seccion-titulo {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #3498db;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .seccion-titulo::before {
            content: "▸";
            color: #3498db;
        }
        
        .grid-datos {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 600px) {
            .grid-datos {
                grid-template-columns: 1fr;
            }
        }
        
        .campo {
            margin-bottom: 12px;
        }
        
        .etiqueta {
            font-weight: bold;
            color: #495057;
            font-size: 14px;
            margin-bottom: 4px;
        }
        
        .valor {
            color: #2c3e50;
            font-size: 15px;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        
        .valor-vacio {
            color: #6c757d;
            font-style: italic;
        }
        
        /* Estado destacado */
        .estado-container {
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            background: <?php echo $estado_info['bgcolor']; ?>;
            border: 2px solid <?php echo $estado_info['color']; ?>;
            border-radius: 8px;
        }
        
        .estado-titulo {
            font-size: 14px;
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .estado-valor {
            font-size: 24px;
            font-weight: bold;
            color: <?php echo $estado_info['color']; ?>;
        }
        
        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 20px 30px;
            text-align: center;
            margin-top: 20px;
        }
        
        .codigo-qr {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px dashed #dee2e6;
        }
        
        .qr-placeholder {
            width: 150px;
            height: 150px;
            background: #e9ecef;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #6c757d;
            font-size: 12px;
        }
        
        /* Estilos para impresión */
        @media print {
            body { 
                background: white; 
                margin: 0;
                padding: 0;
            }
            
            .container { 
                box-shadow: none; 
                margin: 0;
                max-width: 100%;
                border-radius: 0;
            }
            
            .header {
                padding: 20px;
                border-bottom: 3px solid #e74c3c;
            }
            
            .info-container {
                padding: 25px;
            }
            
            .no-print {
                display: none;
            }
            
            .footer {
                margin-top: 30px;
                page-break-inside: avoid;
            }
            
            /* Evitar que se corte una sección entre páginas */
            .seccion {
                page-break-inside: avoid;
            }
        }
        
        /* Botones de acción (no se imprimen) */
        .acciones {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        
        .btn {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>
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

    <script>
        // Auto-imprimir al cargar la página (opcional)
        document.addEventListener('DOMContentLoaded', function() {
            // Descomenta la siguiente línea si quieres que se imprima automáticamente
            // window.print();
        });

        // Cerrar la ventana después de imprimir (opcional)
        window.onafterprint = function() {
            // Descomenta la siguiente línea si quieres cerrar automáticamente después de imprimir
            // setTimeout(() => window.close(), 1000);
        };
    </script>
</body>
</html>