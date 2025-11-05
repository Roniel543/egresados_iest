<?php
/**
 * Controlador Egresado - Maneja toda la lógica de coordinación
 * Coordina entre Model y View, maneja peticiones HTTP
 */

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Egresado.php';
require_once __DIR__ . '/../config/helpers.php';

class EgresadoController {
    private $egresadoModel;
    
    public function __construct() {
        $this->egresadoModel = new Egresado();
    }
    
    /**
     * Manejar formulario de creación/edición
     * Coordina la lógica completa del formulario
     * 
     * @return array Datos para la vista
     */
    public function handleForm() {
        $action = $_GET['action'] ?? 'create';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $egresado = [];
        $mensaje = '';
        $tipo_mensaje = '';
        
        // Obtener mensaje flash si existe
        $flashMessage = getFlashMessage();
        if ($flashMessage) {
            $mensaje = $flashMessage['message'] ?? '';
            $tipo_mensaje = $flashMessage['type'] ?? 'success';
        }
        
        // Procesar formulario POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (isset($_POST['update_egresado'])) {
                    if ($this->egresadoModel->update($_POST)) {
                        setFlashMessage('Egresado actualizado correctamente', 'success');
                        header("Location: NuestrosEgresados.php?action=edit&id=" . $_POST['id_egresado']);
                        exit;
                    } else {
                        throw new Exception('Error al actualizar egresado');
                    }
                } elseif (isset($_POST['create_egresado'])) {
                    if ($this->egresadoModel->create($_POST)) {
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
        if ($action === 'edit' && $id > 0) {
            try {
                $egresado = $this->egresadoModel->getById($id);
                if (!$egresado || empty($egresado)) {
                    $mensaje = 'Egresado no encontrado con ID: ' . $id;
                    $tipo_mensaje = 'error';
                    $action = 'create';
                    $egresado = [];
                }
            } catch (Exception $e) {
                $mensaje = 'Error al obtener egresado: ' . $e->getMessage();
                $tipo_mensaje = 'error';
                $action = 'create';
                $egresado = [];
            }
        }
        
        $programas = $this->egresadoModel->getProgramasEstudio();
        
        return [
            'action' => $action,
            'egresado' => $egresado,
            'programas' => $programas,
            'mensaje' => $mensaje,
            'tipo_mensaje' => $tipo_mensaje
        ];
    }
    
    /**
     * Manejar listado de egresados con búsqueda y paginación
     * 
     * @return array Datos para la vista
     */
    public function handleList() {
        $search = trim($_GET['search'] ?? '');
        $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $por_pagina = 20;
        $offset = ($pagina - 1) * $por_pagina;
        
        // Procesar búsqueda
        if (!empty($search)) {
            $egresados = $this->egresadoModel->search($search, $por_pagina, $offset);
            $total_egresados = $this->egresadoModel->countSearch($search);
        } else {
            $egresados = $this->egresadoModel->getAll($por_pagina, $offset);
            $total_egresados = $this->egresadoModel->count();
        }
        
        // Validar que no sea null
        if ($egresados === null) {
            $egresados = [];
        }
        if ($total_egresados === null) {
            $total_egresados = 0;
        }
        
        $total_paginas = $total_egresados > 0 ? ceil($total_egresados / $por_pagina) : 1;
        $flashMessage = getFlashMessage();
        
        return [
            'egresados' => $egresados,
            'total_egresados' => $total_egresados,
            'total_paginas' => $total_paginas,
            'pagina' => $pagina,
            'search' => $search,
            'flashMessage' => $flashMessage
        ];
    }
    
    /**
     * Manejar vista de impresión de egresado
     * 
     * @param int $id ID del egresado
     * @return array Datos del egresado para la vista
     * @throws Exception Si no se encuentra el egresado
     */
    public function handlePrint($id) {
        if (empty($id) || $id <= 0) {
            throw new Exception('ID de egresado inválido');
        }
        
        $egresado = $this->egresadoModel->getById($id);
        
        if (!$egresado) {
            throw new Exception('Egresado no encontrado con ID: ' . $id);
        }
        
        return $egresado;
    }
    
    // Métodos de acceso directo al Model (para compatibilidad)
    
    public function getEgresadoById($id) {
        return $this->egresadoModel->getById($id);
    }
    
    public function getProgramasEstudio() {
        return $this->egresadoModel->getProgramasEstudio();
    }
    
    public function createEgresado($data) {
        return $this->egresadoModel->create($data);
    }
    
    public function updateEgresado($data) {
        return $this->egresadoModel->update($data);
    }
    
    public function getAllEgresados($limit = null, $offset = 0) {
        return $this->egresadoModel->getAll($limit, $offset);
    }
    
    public function countEgresados() {
        return $this->egresadoModel->count();
    }
    
    public function searchEgresados($searchTerm, $limit = null, $offset = 0) {
        return $this->egresadoModel->search($searchTerm, $limit, $offset);
    }
    
    public function countSearchEgresados($searchTerm) {
        return $this->egresadoModel->countSearch($searchTerm);
    }
}