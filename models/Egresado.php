<?php
/**
 * Modelo Egresado
 * 
 * Maneja todas las operaciones de base de datos relacionadas con egresados
 * Usa PDO para todas las consultas
 */

class Egresado {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Obtener egresado por ID
     * 
     * @param int $id ID del egresado
     * @return array|null Datos del egresado o null si no existe
     */
    public function getById($id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT e.*, p.nombre_programa 
                               FROM egresados e 
                               JOIN programas_estudio p ON e.id_programa = p.id_programa 
                               WHERE e.id_egresado = ?");
        $stmt->execute([intval($id)]); // Asegurar que sea entero
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Si no encuentra nada, fetch devuelve false, convertir a null
        return $result ? $result : null;
    }
    
    /**
     * Obtener todos los egresados con paginación
     * 
     * @param int|null $limit Límite de resultados
     * @param int $offset Offset para paginación
     * @return array Lista de egresados
     */
    public function getAll($limit = null, $offset = 0) {
        $conn = $this->db->getConnection();
        $sql = "SELECT e.*, p.nombre_programa 
                FROM egresados e 
                JOIN programas_estudio p ON e.id_programa = p.id_programa 
                ORDER BY e.apellidos, e.nombres";
        
        if ($limit !== null) {
            // Convertir a enteros para seguridad
            $limit = (int)$limit;
            $offset = (int)$offset;
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Contar total de egresados
     * 
     * @return int Total de egresados
     */
    public function count() {
        $conn = $this->db->getConnection();
        $stmt = $conn->query("SELECT COUNT(*) as total FROM egresados");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }
    
    /**
     * Buscar egresados por DNI o nombre
     * 
     * @param string $searchTerm Término de búsqueda
     * @param int|null $limit Límite de resultados
     * @param int $offset Offset para paginación
     * @return array Lista de egresados encontrados
     */
    public function search($searchTerm, $limit = null, $offset = 0) {
        $conn = $this->db->getConnection();
        $sql = "SELECT e.*, p.nombre_programa 
                FROM egresados e 
                JOIN programas_estudio p ON e.id_programa = p.id_programa 
                WHERE e.dni LIKE ? OR e.nombres LIKE ? OR e.apellidos LIKE ? 
                ORDER BY e.apellidos, e.nombres";
        
        $search = "%{$searchTerm}%";
        
        if ($limit !== null) {
            // Convertir a enteros para seguridad
            $limit = (int)$limit;
            $offset = (int)$offset;
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$search, $search, $search]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Contar resultados de búsqueda
     * 
     * @param string $searchTerm Término de búsqueda
     * @return int Total de resultados
     */
    public function countSearch($searchTerm) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) as total 
                            FROM egresados 
                            WHERE dni LIKE ? OR nombres LIKE ? OR apellidos LIKE ?");
        
        $search = "%{$searchTerm}%";
        $stmt->execute([$search, $search, $search]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['total'];
    }
    
    /**
     * Verificar si DNI existe
     * 
     * @param string $dni DNI a verificar
     * @param int|null $excludeId ID a excluir (para edición)
     * @return bool True si existe
     */
    public function dniExists($dni, $excludeId = null) {
        $conn = $this->db->getConnection();
        
        if ($excludeId) {
            $stmt = $conn->prepare("SELECT id_egresado FROM egresados WHERE dni = ? AND id_egresado != ?");
            $stmt->execute([$dni, $excludeId]);
        } else {
            $stmt = $conn->prepare("SELECT id_egresado FROM egresados WHERE dni = ?");
            $stmt->execute([$dni]);
        }
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Validar datos de egresado (LÓGICA DE NEGOCIO)
     * 
     * @param array $data Datos a validar
     * @throws Exception Si hay errores de validación
     */
    private function validarDatos($data) {
        // Validar DNI
        if (empty($data['dni']) || !preg_match('/^\d{8}$/', $data['dni'])) {
            throw new Exception("El DNI debe tener 8 dígitos");
        }
        
        // Validar nombres
        if (empty(trim($data['nombres']))) {
            throw new Exception("Los nombres son obligatorios");
        }
        
        // Validar apellidos
        if (empty(trim($data['apellidos']))) {
            throw new Exception("Los apellidos son obligatorios");
        }
        
        // Validar años
        $año_actual = date('Y');
        $año_ingreso = intval($data['año_ingreso']);
        $año_egreso = intval($data['año_egreso']);
        
        // Validar año de ingreso
        if ($año_ingreso < 1978 || $año_ingreso > $año_actual) {
            throw new Exception("Año de ingreso inválido. Debe estar entre 1978 y " . $año_actual);
        }
        
        // Validar año de egreso
        if ($año_egreso < $año_ingreso) {
            throw new Exception("El año de egreso no puede ser menor al año de ingreso");
        }
        
        if ($año_egreso > $año_actual) {
            throw new Exception("El año de egreso no puede ser mayor al año actual (" . $año_actual . ")");
        }
        
        // Validar email si está presente
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Formato de email inválido");
        }
    }
    
    /**
     * Sanitizar datos de entrada
     * 
     * @param array $data Datos a sanitizar
     * @return array Datos sanitizados
     */
    private function sanitizarDatos($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }
    
    /**
     * Crear nuevo egresado
     * 
     * @param array $data Datos del egresado
     * @return bool True si se creó correctamente
     * @throws Exception Si hay errores de validación
     */
    public function create($data) {
        // Validar datos (lógica de negocio)
        $this->validarDatos($data);
        
        // Sanitizar datos
        $data = $this->sanitizarDatos($data);
        
        // Verificar DNI único
        if ($this->dniExists($data['dni'])) {
            throw new Exception("El DNI ya está registrado");
        }
        
        // Acceso a datos (INSERT)
        $conn = $this->db->getConnection();
        $sql = "INSERT INTO egresados 
                (dni, nombres, apellidos, fecha_nacimiento, sexo, estado_civil, 
                 id_programa, año_ingreso, año_egreso, estado_actual, telefono, email, direccion, donde_labora_actualmente, fecha_registro) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $data['dni'],
            $data['nombres'],
            $data['apellidos'],
            $data['fecha_nacimiento'],
            $data['sexo'],
            $data['estado_civil'],
            $data['id_programa'],
            $data['año_ingreso'],
            $data['año_egreso'],
            $data['estado_actual'],
            $data['telefono'] ?? null,
            $data['email'] ?? null,
            $data['direccion'] ?? null,
            $data['donde_labora_actualmente'] ?? null
        ]);
    }
    
    /**
     * Actualizar egresado existente
     * 
     * @param array $data Datos del egresado (debe incluir id_egresado)
     * @return bool True si se actualizó correctamente
     * @throws Exception Si hay errores de validación
     */
    public function update($data) {
        // Validar datos (lógica de negocio)
        $this->validarDatos($data);
        
        // Sanitizar datos
        $data = $this->sanitizarDatos($data);
        
        // Verificar DNI único (excluyendo el actual)
        if ($this->dniExists($data['dni'], $data['id_egresado'])) {
            throw new Exception("El DNI ya está registrado");
        }
        
        // Acceso a datos (UPDATE)
        $conn = $this->db->getConnection();
        $sql = "UPDATE egresados SET 
                dni = ?, nombres = ?, apellidos = ?, fecha_nacimiento = ?, 
                sexo = ?, estado_civil = ?, id_programa = ?, año_ingreso = ?, 
                año_egreso = ?, estado_actual = ?, telefono = ?, email = ?, direccion = ?, donde_labora_actualmente = ?,
                fecha_actualizacion = NOW()
                WHERE id_egresado = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $data['dni'],
            $data['nombres'],
            $data['apellidos'],
            $data['fecha_nacimiento'],
            $data['sexo'],
            $data['estado_civil'],
            $data['id_programa'],
            $data['año_ingreso'],
            $data['año_egreso'],
            $data['estado_actual'],
            $data['telefono'] ?? null,
            $data['email'] ?? null,
            $data['direccion'] ?? null,
            $data['donde_labora_actualmente'] ?? null,
            $data['id_egresado']
        ]);
    }
    
    /**
     * Obtener programas de estudio activos
     * 
     * @return array Lista de programas
     */
    public function getProgramasEstudio() {
        $conn = $this->db->getConnection();
        $stmt = $conn->query("SELECT * FROM programas_estudio WHERE activo = 1 ORDER BY nombre_programa");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
