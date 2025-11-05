<?php
/**
 * Entry Point - Formulario de egresados
 */

require_once '../config/conexion.php';
require_once '../controllers/EgresadoController.php';
require_once '../config/helpers.php';

// Configuración de errores (solo desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión
session_start();

// Usar Controller para manejar toda la lógica
$controller = new EgresadoController();
$data = $controller->handleForm();

// Extraer variables para la vista
extract($data);

// Incluir vista (VIEW)
include '../views/egresados/form.php';
