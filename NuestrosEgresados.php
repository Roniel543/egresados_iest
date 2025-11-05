<?php
// Configuración básica
include 'config/conexion.php';
require_once 'models/Egresado.php';
require_once 'controllers/EgresadoController.php';
require_once 'config/helpers.php';

// Configuración de errores (solo desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión
session_start();

// Inicializar variables
$action = $_GET['action'] ?? 'create';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Convertir a entero

$egresadoObj = new EgresadoController();
// Procesar formulario
$mensaje = '';
$tipo_mensaje = '';

// Obtener mensaje flash si existe (después de redirección)
$flashMessage = getFlashMessage();
if ($flashMessage) {
    $mensaje = $flashMessage['message'] ?? '';
    $tipo_mensaje = $flashMessage['type'] ?? 'success';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['update_egresado'])) {
            if ($egresadoObj->updateEgresado($_POST)) {
                // Guardar mensaje en sesión y redirigir
                setFlashMessage('Egresado actualizado correctamente', 'success');
                header("Location: NuestrosEgresados.php?action=edit&id=" . $_POST['id_egresado']);
                exit;
            } else {
                throw new Exception('Error al actualizar egresado');
            }
        } elseif (isset($_POST['create_egresado'])) {
            if ($egresadoObj->createEgresado($_POST)) {
                // Guardar mensaje en sesión y redirigir (formulario limpio)
                setFlashMessage('Egresado registrado exitosamente. Gracias por completar tu registro.', 'success');
                header("Location: NuestrosEgresados.php");
                exit;
            } else {
                throw new Exception('Error al crear egresado');
            }
        }
    } catch (Exception $e) {
        $mensaje = $e->getMessage();
        $tipo_mensaje = 'error';
    }
}

// Obtener datos del egresado si es edición
$egresado = [];
if ($action === 'edit' && $id > 0) {
    try {
    $egresado = $egresadoObj->getEgresadoById($id);
        if (!$egresado || empty($egresado)) {
            $mensaje = 'Egresado no encontrado con ID: ' . $id;
            $tipo_mensaje = 'error';
            $action = 'create';
            $egresado = []; // Asegurar que esté vacío
        }
    } catch (Exception $e) {
        $mensaje = 'Error al obtener egresado: ' . $e->getMessage();
        $tipo_mensaje = 'error';
        $action = 'create';
        $egresado = [];
    }
}

$programas = $egresadoObj->getProgramasEstudio();

// Incluir vista (VIEW)
include 'views/egresados/form.php';