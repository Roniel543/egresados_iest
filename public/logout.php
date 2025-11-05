<?php
/**
 * Logout - Cerrar sesión
 */

require_once '../config/helpers.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cerrar sesión
logout();

// Redirigir al login
header("Location: login.php");
exit;

