<?php
/**
 * Funciones auxiliares básicas
 * Los helpers son funciones que se usan frecuentemente en la aplicación y se usan para no repetir código
 */

// Función para obtener mensaje flash
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

// Función para establecer mensaje flash
function setFlashMessage($mensaje, $tipo = 'success') {
    $_SESSION['flash_message'] = [
        'message' => $mensaje,
        'type' => $tipo
    ];
}

// Función para escapar HTML
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Función para formatear fecha
function formatearFecha($fecha) {
    if (empty($fecha) || $fecha == '0000-00-00') {
        return 'No especificada';
    }
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha);
    return $fecha_obj ? $fecha_obj->format('d/m/Y') : 'Fecha inválida';
}

// Función para calcular edad
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

// Función para obtener información del estado
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

// Auth

/**
 * Verificar si el usuario está autenticado
 * 
 * @return bool True si está autenticado
 */
function isAuthenticated() {
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

/**
 * Requerir autenticación - redirige a login si no está autenticado
 */
function requireAuth() {
    if (!isAuthenticated()) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Autenticar usuario
 * 
 * @param string $usuario
 * @param string $password
 * @return bool True si las credenciales son correctas
 */
function authenticate($usuario, $password) {
    // Credenciales válidas (usuario: admin, contraseña: admin123)
    
    $validUsers = [
        'admin' => 'admin123',
        // Agregar más usuarios aquí si es necesario
        // Ejemplo: 'otro_usuario' => 'su_contraseña',
    ];
    
    // Verificar si el usuario existe
    if (!isset($validUsers[$usuario])) {
        return false;
    }
    
    // Verificar contraseña (comparación directa para desarrollo)
    // En producción, usar password_verify() con contraseñas hasheadas
    if ($validUsers[$usuario] === $password) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['authenticated'] = true;
        $_SESSION['usuario'] = $usuario;
        return true;
    }
    
    return false;
}

/**
 * Cerrar sesión
 */
function logout() {
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION = [];
    session_destroy();
}

