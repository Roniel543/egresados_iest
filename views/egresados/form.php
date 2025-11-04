<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $action === 'create' ? 'Nuevo Egresado' : 'Editar Egresado'; ?> - IEST La Recoleta</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; line-height: 1.6; }
        .container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; }
        
        /* Cabecera */
        .header { background: linear-gradient(135deg, #2c3e50, #3498db); color: white; padding: 20px 25px; }
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .logo { display: flex; align-items: center; gap: 15px; }
        .logo img { height: 60px; }
        .logo-text h1 { font-size: 24px; margin: 0; }
        .logo-text p { font-size: 14px; opacity: 0.9; margin: 0; }
        
        /* Navegación */
        .nav { background: #34495e; padding: 10px 25px; }
        .nav-links { display: flex; gap: 15px; }
        .nav-links a { color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.1); }
        
        /* Contenido principal */
        .main-content { padding: 25px; }
        
        .form-container { background: white; padding: 30px; border-radius: 10px; margin-top: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; transition: all 0.3s ease; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #3498db; box-shadow: 0 0 5px rgba(52, 152, 219, 0.3); outline: none; }
        .form-row { display: flex; gap: 20px; margin-bottom: 15px; }
        .form-row .form-group { flex: 1; }
        
        .btn { padding: 10px 18px; margin: 5px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: 600; transition: all 0.3s ease; font-size: 14px; }
        .btn-success { background: #27ae60; color: white; }
        .btn-success:hover { background: #219653; transform: translateY(-2px); }
        .btn-warning { background: #f39c12; color: white; }
        .btn-warning:hover { background: #e67e22; transform: translateY(-2px); }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #545b62; transform: translateY(-2px); }
        
        /* Footer */
        .footer { background: #2c3e50; color: white; padding: 20px 25px; margin-top: 30px; }
        .footer-content { display: flex; justify-content: space-between; align-items: center; }
        .footer-info { flex: 1; }
        .footer-links { display: flex; gap: 15px; }
        .footer-links a { color: #ecf0f1; text-decoration: none; }
        .footer-links a:hover { color: #3498db; }
        
        @media (max-width: 768px) {
            .form-row { flex-direction: column; gap: 0; }
            .header-top { flex-direction: column; gap: 10px; text-align: center; }
            .footer-content { flex-direction: column; gap: 15px; text-align: center; }
        }
        
        .alert { 
            padding: 20px 25px; 
            margin: 20px 0; 
            border-radius: 10px; 
            font-weight: 600; 
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            animation: slideDown 0.5s ease-out;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .alert-success { 
            background: linear-gradient(135deg, #27ae60, #2ecc71); 
            color: white; 
            border: 2px solid #1e8449;
            box-shadow: 0 4px 20px rgba(39, 174, 96, 0.4);
        }
        .alert-error { 
            background: linear-gradient(135deg, #e74c3c, #c0392b); 
            color: white; 
            border: 2px solid #922b21;
            box-shadow: 0 4px 20px rgba(231, 76, 60, 0.4);
        }
        .alert-icon {
            font-size: 28px;
            flex-shrink: 0;
        }
        .required::after { content: " *"; color: #e74c3c; }
        .form-note { 
            font-size: 12px; 
            color: #6c757d; 
            margin-top: 5px; 
            display: block;
            line-height: 1.4;
        }
        .form-group input:invalid {
            border-color: #e74c3c;
        }
        .form-group input:valid:not(:placeholder-shown) {
            border-color: #27ae60;
        }
        h1 { color: #ffcc33; margin-bottom: 25px; text-align: center; padding-bottom: 15px; border-bottom: 2px solid #e9ecef; }
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
                <a href="index.php">🏠 Volver al Inicio</a>
                <a href="NuestrosEgresados.php">➕ Nuevo Egresado</a>
                <a href="https://www.iestlarecoleta.edu.pe" target="_blank">🌐 Sitio Web</a>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="main-content">
            <h1><?php echo $action === 'create' ? '📝 NUEVO EGRESADO' : '✏️ EDITAR EGRESADO'; ?></h1>
            
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
                        <?php if ($action === 'create'): ?>
                            <button type="submit" name="create_egresado" class="btn btn-success">💾 Guardar Egresado</button>
                        <?php else: ?>
                            <button type="submit" name="update_egresado" class="btn btn-warning">✏️ Actualizar Egresado</button>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-secondary">📋 Volver al Inicio</a>
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

    <script>
        // Validación del lado del cliente
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                let valid = true;
                let mensajesError = [];
                
                // Validar DNI
                const dni = form.querySelector('input[name="dni"]');
                if (dni && !/^\d{8}$/.test(dni.value)) {
                    mensajesError.push('El DNI debe tener exactamente 8 dígitos');
                    valid = false;
                }
                
                // Validar años básicos
                const añoIngreso = document.getElementById('año_ingreso');
                const añoEgreso = document.getElementById('año_egreso');
                if (añoIngreso && añoEgreso && añoIngreso.value && añoEgreso.value) {
                    const ingreso = parseInt(añoIngreso.value);
                    const egreso = parseInt(añoEgreso.value);
                    const añoActual = new Date().getFullYear();
                    
                    if (egreso < ingreso) {
                        mensajesError.push('El año de egreso no puede ser menor al año de ingreso');
                        valid = false;
                    } else if (egreso > añoActual) {
                        mensajesError.push(`El año de egreso no puede ser mayor al año actual (${añoActual})`);
                        valid = false;
                    }
                }
                
                if (!valid) {
                    e.preventDefault();
                    alert('Por favor corrija los siguientes errores:\n\n' + mensajesError.join('\n'));
                }
            });
            
            // Auto-eliminar mensajes de éxito después de 8 segundos (más tiempo para que se vea)
            const successAlerts = document.querySelectorAll('.alert-success');
            successAlerts.forEach(alert => {
                // Hacer scroll suave al mensaje
                alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Auto-eliminar después de 8 segundos
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    alert.style.transition = 'all 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                }, 8000); // 8 segundos para mensajes de éxito
            });
            
            // Auto-eliminar mensajes de error después de 10 segundos
            const errorAlerts = document.querySelectorAll('.alert-error');
            errorAlerts.forEach(alert => {
                alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    alert.style.transition = 'all 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                }, 10000); // 10 segundos para errores
            });
        });
    </script>
</body>
</html>

