<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $action === 'create' ? 'Nuevo Egresado' : 'Editar Egresado'; ?> - IEST La Recoleta</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Estilos adicionales para asegurar visibilidad de opciones del select */
        select option {
            background-color: #ffffff;
            color: #212529;
        }
        select option:hover,
        select option:focus {
            background-color: #ffc107;
            color: #030f27;
        }
        select option:checked {
            background-color: rgba(255, 193, 7, 0.3);
            color: #030f27;
            font-weight: 600;
        }
        /* Asegurar que el select tenga fondo blanco y texto oscuro */
        select {
            background-color: #ffffff;
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabecera -->
        <header class="header">
            <div class="header-top">
                <div class="logo">
                    <img src="logo.png" alt="Logo IEST La Recoleta" onerror="this.style.display='none'">
                    <div class="logo-text">
                        <h1>IEST "LA RECOLETA"</h1>
                        <p>Sistema de Gestión de Egresados</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navegación -->
        <nav class="nav">
            <div class="nav-links">
                <a href="NuestrosEgresadosadmin.php">Volver al Inicio</a>
                <a href="NuestrosEgresados.php">Nuevo Egresado</a>
                <a href="https://www.iestlarecoleta.edu.pe" target="_blank">Sitio Web</a>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="main-content">
            <h1><?php echo $action === 'create' ? 'NUEVO EGRESADO' : 'EDITAR EGRESADO'; ?></h1>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje === 'success' ? 'success' : 'error'; ?>">
                    
                    <span><?php echo htmlspecialchars($mensaje); ?></span>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST">
                    <?php if ($action === 'edit'): ?>
                        <input type="hidden" name="id_egresado" value="<?php echo $egresado['id_egresado']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">DNI</label>
                            <input type="text" name="dni" value="<?php echo htmlspecialchars($egresado['dni'] ?? ''); ?>" 
                                   pattern="\d{8}" title="El DNI debe tener 8 dígitos" required>
                            <div class="form-note">8 dígitos sin espacios ni puntos</div>
                        </div>
                        <div class="form-group">
                            <label class="required">Nombres</label>
                            <input type="text" name="nombres" value="<?php echo htmlspecialchars($egresado['nombres'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Apellidos</label>
                            <input type="text" name="apellidos" value="<?php echo htmlspecialchars($egresado['apellidos'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" 
                                   value="<?php echo htmlspecialchars($egresado['fecha_nacimiento'] ?? ''); ?>" 
                                   max="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Sexo</label>
                            <select name="sexo" required>
                                <option value="">Seleccionar...</option>
                                <option value="Masculino" <?php echo ($egresado['sexo'] ?? '') === 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                <option value="Femenino" <?php echo ($egresado['sexo'] ?? '') === 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                                <option value="Otro" <?php echo ($egresado['sexo'] ?? '') === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Estado Civil</label>
                            <select name="estado_civil" required>
                                <option value="">Seleccionar...</option>
                                <option value="Soltero(a)" <?php echo ($egresado['estado_civil'] ?? '') === 'Soltero(a)' ? 'selected' : ''; ?>>Soltero(a)</option>
                                <option value="Casado(a)" <?php echo ($egresado['estado_civil'] ?? '') === 'Casado(a)' ? 'selected' : ''; ?>>Casado(a)</option>
                                <option value="Divorciado(a)" <?php echo ($egresado['estado_civil'] ?? '') === 'Divorciado(a)' ? 'selected' : ''; ?>>Divorciado(a)</option>
                                <option value="Viudo(a)" <?php echo ($egresado['estado_civil'] ?? '') === 'Viudo(a)' ? 'selected' : ''; ?>>Viudo(a)</option>
                                <option value="Unión Libre" <?php echo ($egresado['estado_civil'] ?? '') === 'Unión Libre' ? 'selected' : ''; ?>>Unión Libre</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Programa de Estudios</label>
                            <select name="id_programa" required>
                                <option value="">Seleccionar programa...</option>
                                <?php foreach ($programas as $programa): ?>
                                    <option value="<?php echo $programa['id_programa']; ?>" 
                                        <?php echo ($egresado['id_programa'] ?? '') == $programa['id_programa'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($programa['nombre_programa']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Año de Ingreso</label>
                            <input type="number" name="año_ingreso" id="año_ingreso"
                                   value="<?php echo htmlspecialchars($egresado['año_ingreso'] ?? ''); ?>" 
                                   min="1978" max="<?php echo date('Y'); ?>" required>
                            <div class="form-note">Ingrese el año en que comenzó la carrera</div>
                        </div>
                        <div class="form-group">
                            <label class="required">Año de Egreso</label>
                            <input type="number" name="año_egreso" id="año_egreso"
                                   value="<?php echo htmlspecialchars($egresado['año_egreso'] ?? ''); ?>" 
                                   min="1978" max="<?php echo date('Y'); ?>" required>
                            <div class="form-note">Ingrese el año en que egresó</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Estado Actual</label>
                            <select name="estado_actual" required>
                                <option value="">Seleccionar...</option>
                                <option value="EGRESADO" <?php echo ($egresado['estado_actual'] ?? '') === 'EGRESADO' ? 'selected' : ''; ?>>Egresado</option>
                                <option value="TITULADO" <?php echo ($egresado['estado_actual'] ?? '') === 'TITULADO' ? 'selected' : ''; ?>>Titulado</option>
                                <option value="CERTIFICADO" <?php echo ($egresado['estado_actual'] ?? '') === 'CERTIFICADO' ? 'selected' : ''; ?>>Certificado</option>
                                <option value="EN PROCESO" <?php echo ($egresado['estado_actual'] ?? '') === 'EN PROCESO' ? 'selected' : ''; ?>>En Proceso</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="tel" name="telefono" value="<?php echo htmlspecialchars($egresado['telefono'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($egresado['email'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Dirección</label>
                        <textarea name="direccion" rows="3" placeholder="Ingrese la dirección completa..."><?php echo htmlspecialchars($egresado['direccion'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>¿Dónde se encuentra actualmente laborando?</label>
                        <input type="text" name="donde_labora_actualmente" 
                               value="<?php echo htmlspecialchars($egresado['donde_labora_actualmente'] ?? ''); ?>" 
                               placeholder="Ej: Empresa XYZ, Institución ABC, Emprendimiento propio, etc.">
                        <div class="form-note">Indique el lugar donde trabaja actualmente (opcional)</div>
                    </div>

                    <div class="form-group" style="margin-top: 30px; text-align: center; padding-top: 20px; border-top: 2px solid rgba(3, 15, 39, 0.1);">
                        <?php if ($action === 'create'): ?>
                            <button type="submit" name="create_egresado" class="btn btn-success">Guardar Egresado</button>
                        <?php else: ?>
                            <button type="submit" name="update_egresado" class="btn btn-warning">Actualizar Egresado</button>
                        <?php endif; ?>
                        <a href="NuestrosEgresadosadmin.php" class="btn btn-secondary">Volver al Inicio</a>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-info">
                    <p><strong>IEST "LA RECOLETA"</strong></p>
                    <p>Sistema de Gestión de Egresados &copy; <?php echo date('Y'); ?></p>
                </div>
                <div class="footer-links">
                    <a href="https://www.iestlarecoleta.edu.pe" target="_blank">Sitio Web</a>
                    <a href="mailto:informes@iestlarecoleta.edu.pe">Contacto</a>
                </div>
            </div>
        </footer>
    </div>

    <script src="js/form-validation.js"></script>
</body>
</html>

