<?php
/**
 * Página de Login - Sistema de autenticación
 */

require_once '../config/helpers.php';

// Iniciar sesión
session_start();

// Si ya está autenticado, redirigir al admin
if (isAuthenticated()) {
    header("Location: NuestrosEgresadosadmin.php");
    exit;
}

$error = '';

// Procesar formulario de logins
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($usuario) || empty($password)) {
        $error = 'Por favor, complete todos los campos';
    } else {
        if (authenticate($usuario, $password)) {
            header("Location: NuestrosEgresadosadmin.php");
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestión de Egresados</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(3, 15, 39, 0.95), rgba(3, 15, 39, 0.85));
            padding: 20px;
        }
        
        .login-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: var(--color-primary-dark);
            margin-bottom: 10px;
            border: none;
            padding: 0;
        }
        
        .login-header h1::before {
            display: none;
        }
        
        .login-header p {
            color: #6c757d;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--color-primary-dark);
            font-weight: 600;
            text-align: center;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--color-primary-accent);
        }
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
            text-align: center;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--color-primary-dark);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-login:hover {
            background: rgba(3, 15, 39, 0.9);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 12px;
        }
        
        .login-footer p {
            text-align: center;
        }
        
        .login-footer a {
            display: block;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>ACCESO</h1>
                <p>IEST "LA RECOLETA"</p>
                <p>Sistema de Gestión de Egresados</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>
            
            <div class="login-footer">
                <p>Acceso restringido al personal autorizado</p>
                <a href="NuestrosEgresados.php" style="color: var(--color-primary-accent); text-decoration: none;">
                    ← Volver al formulario público
                </a>
            </div>
        </div>
    </div>
</body>
</html>

