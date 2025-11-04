<?php
/**
 * Controlador Egresado - VERSIÓN SIMPLE
 * Solo maneja las operaciones básicas que necesita NuestrosEgresados.php
 */

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Egresado.php';

class EgresadoController {
    private $egresadoModel;
    
    public function __construct() {
        $this->egresadoModel = new Egresado();
    }
    
    /**
     * Obtener egresado por ID (para compatibilidad con código viejo)
     */
    public function getEgresadoById($id) {
        return $this->egresadoModel->getById($id);
    }
    
    /**
     * Obtener programas de estudio
     */
    public function getProgramasEstudio() {
        return $this->egresadoModel->getProgramasEstudio();
    }
    
    /**
     * Crear nuevo egresado
     * CONTROLLER: Solo coordina, NO valida ni sanitiza (eso es del Model)
     */
    public function createEgresado($data) {
        // El Model se encarga de validar, sanitizar y crear
        return $this->egresadoModel->create($data);
    }
    
    /**
     * Actualizar egresado
     * CONTROLLER: Solo coordina, NO valida ni sanitiza (eso es del Model)
     */
    public function updateEgresado($data) {
        // El Model se encarga de validar, sanitizar y actualizar
        return $this->egresadoModel->update($data);
    }
    
    // Métodos para compatibilidad con código viejo (si se necesitan)
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