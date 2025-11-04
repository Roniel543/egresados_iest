<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $action === 'create' ? 'Nuevo Egresado' : 'Editar Egresado'; ?> - IEST La Recoleta</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; line-height: 1.6; }
        .container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; }
        
        /* Cabecera Académica Elegante - Diseño La Recoleta */
        .header { 
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 50%, #1976d2 100%); 
            color: white; 
            padding: 25px 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-bottom: 5px solid #ffc107;
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #ffc107, transparent);
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
        
        /* Navegación Académica - Diseño La Recoleta */
        .nav { 
            background: linear-gradient(135deg, #1565c0, #0d47a1); 
            padding: 12px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 2px solid rgba(255, 193, 7, 0.3);
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
        
        /* Contenedor del formulario elegante */
        .form-container { 
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%); 
            padding: 40px; 
            border-radius: 12px; 
            margin-top: 20px; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            border: 2px solid #e3f2fd;
        }
        .form-group { margin-bottom: 25px; }
        .form-group label { 
            display: block; 
            margin-bottom: 10px; 
            font-weight: 700; 
            color: #0d47a1;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-group input, .form-group select, .form-group textarea { 
            width: 100%; 
            padding: 14px 16px; 
            border: 2px solid #e0e0e0; 
            border-radius: 8px; 
            font-size: 15px; 
            transition: all 0.3s ease;
            background: white;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { 
            border-color: #1565c0; 
            box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.1); 
            outline: none;
        }
        .form-row { display: flex; gap: 20px; margin-bottom: 15px; }
        .form-row .form-group { flex: 1; }
        
        /* Botones elegantes - Diseño La Recoleta */
        .btn { 
            padding: 14px 24px; 
            margin: 5px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            text-decoration: none; 
            display: inline-block; 
            font-weight: 700; 
            transition: all 0.3s ease; 
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .btn-success { 
            background: linear-gradient(135deg, #2e7d32, #43a047); 
            color: white; 
        }
        .btn-success:hover { 
            background: linear-gradient(135deg, #1b5e20, #2e7d32); 
            transform: translateY(-2px); 
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.4);
        }
        .btn-warning { 
            background: linear-gradient(135deg, #f57c00, #fb8c00); 
            color: white; 
        }
        .btn-warning:hover { 
            background: linear-gradient(135deg, #e65100, #f57c00); 
            transform: translateY(-2px); 
            box-shadow: 0 4px 12px rgba(245, 124, 0, 0.4);
        }
        .btn-secondary { 
            background: linear-gradient(135deg, #546e7a, #607d8b); 
            color: white; 
        }
        .btn-secondary:hover { 
            background: linear-gradient(135deg, #37474f, #546e7a); 
            transform: translateY(-2px); 
            box-shadow: 0 4px 12px rgba(84, 110, 122, 0.4);
        }
        
        /* Footer Académico - Diseño La Recoleta */
        .footer { 
            background: linear-gradient(135deg, #0d47a1, #1565c0); 
            color: white; 
            padding: 30px 40px; 
            margin-top: 40px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
            border-top: 5px solid #ffc107;
        }
        .footer-content { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .footer-info { flex: 1; }
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
            background: linear-gradient(135deg, #2e7d32, #43a047); 
            color: white; 
            border-left: 5px solid #4caf50;
            box-shadow: 0 4px 20px rgba(46, 125, 50, 0.4);
        }
        .alert-error { 
            background: linear-gradient(135deg, #c62828, #d32f2f); 
            color: white; 
            border-left: 5px solid #f44336;
            box-shadow: 0 4px 20px rgba(198, 40, 40, 0.4);
        }
        .alert-icon {
            font-size: 28px;
            flex-shrink: 0;
        }
        .required::after { 
            content: " *"; 
            color: #d32f2f; 
            font-weight: 800;
            font-size: 16px;
        }
        .form-note { 
            font-size: 13px; 
            color: #546e7a; 
            margin-top: 8px; 
            display: block;
            line-height: 1.5;
            font-style: italic;
        }
        .form-group input:invalid {
            border-color: #d32f2f;
        }
        .form-group input:valid:not(:placeholder-shown) {
            border-color: #43a047;
        }
        .form-group select {
            cursor: pointer;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        /* Título principal elegante */
        h1 { 
            color: #0d47a1; 
            margin-bottom: 30px; 
            text-align: center; 
            padding-bottom: 20px; 
            border-bottom: 4px solid #ffc107;
            font-size: 36px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
        }
        h1::before {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 4px;
            background: #ffc107;
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
                <a href="index.php">Volver al Inicio</a>
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

                    <div class="form-group" style="margin-top: 30px; text-align: center; padding-top: 20px; border-top: 2px solid #e3f2fd;">
                        <?php if ($action === 'create'): ?>
                            <button type="submit" name="create_egresado" class="btn btn-success">Guardar Egresado</button>
                        <?php else: ?>
                            <button type="submit" name="update_egresado" class="btn btn-warning">Actualizar Egresado</button>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
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

