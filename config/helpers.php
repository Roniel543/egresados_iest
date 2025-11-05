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

