-- ============================================
-- SISTEMA DE GESTIÓN RRHH - BASE DE DATOS
-- ============================================

CREATE DATABASE IF NOT EXISTS rrhh_sistema;
USE rrhh_sistema;

-- ============================================
-- TABLAS MAESTRAS (CATÁLOGOS)
-- ============================================

-- 1. PAÍS
CREATE TABLE pais (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    codigo_iso VARCHAR(2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. ESTADO
CREATE TABLE estado (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    pais_id INT NOT NULL,
    capital VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pais_id) REFERENCES pais(id) ON DELETE CASCADE
);

-- 3. CIUDAD
CREATE TABLE ciudad (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    estado_id INT NOT NULL,
    codigo_postal VARCHAR(20),
    zona_horaria VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estado_id) REFERENCES estado(id) ON DELETE CASCADE
);

-- 4. TIPO INSTITUCIÓN
CREATE TABLE tipo_institucion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 5. INSTITUCIÓN
CREATE TABLE institucion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    tipo_institucion_id INT NOT NULL,
    ubicacion VARCHAR(100),
    telefono VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_institucion_id) REFERENCES tipo_institucion(id) ON DELETE CASCADE
);

-- 6. IDIOMA
CREATE TABLE idioma (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    codigo_iso VARCHAR(2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 7. TIPO PERMISO
CREATE TABLE tipo_permiso (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    categoria ENUM('Concesión Obligatoria', 'Concesión Potestativa', 'Justificación de Ausencia') NOT NULL,
    dias INT DEFAULT 0,
    requiere_soporte ENUM('Sí', 'No') DEFAULT 'Sí',
    base_legal TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 8. DOCUMENTO
CREATE TABLE documento (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    categoria ENUM('Identificación', 'Académico', 'Laboral', 'Médico', 'Legal') DEFAULT 'Identificación',
    formato ENUM('PDF', 'Imagen', 'Ambos') DEFAULT 'PDF',
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLA PRINCIPAL: EMPLEADO
-- ============================================

CREATE TABLE empleado (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    fecha_ingreso DATE NOT NULL,
    cargo VARCHAR(50) NOT NULL,
    departamento ENUM('Tecnología', 'Recursos Humanos', 'Finanzas', 'Marketing', 'Ventas', 'Administración') NOT NULL,
    estatus ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLAS DE MAESTROS RELACIONADOS CON EMPLEADO
-- ============================================

-- 9. EXTRANJERO
CREATE TABLE extranjero (
    id INT PRIMARY KEY AUTO_INCREMENT,
    empleado_id INT NOT NULL,
    pais_origen VARCHAR(50) NOT NULL,
    pasaporte VARCHAR(20) NOT NULL,
    fecha_emision DATE,
    fecha_vencimiento DATE,
    tipo_visa ENUM('Trabajo', 'Estudiante', 'Residencia', 'Turista') DEFAULT 'Trabajo',
    tiempo_estadia VARCHAR(20),
    permiso_trabajo ENUM('Sí', 'No', 'En trámite') DEFAULT 'Sí',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleado(id) ON DELETE CASCADE
);

-- 10. NACIONALIZACIÓN
CREATE TABLE nacionalizacion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    empleado_id INT NOT NULL,
    pais_origen VARCHAR(50) NOT NULL,
    gaceta VARCHAR(20) NOT NULL,
    fecha_gaceta DATE,
    fecha_nacionalizacion DATE,
    cedula VARCHAR(20),
    estado ENUM('Completado', 'En proceso', 'Pendiente') DEFAULT 'Pendiente',
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleado(id) ON DELETE CASCADE
);

-- 11. FAMILIA
CREATE TABLE familia (
    id INT PRIMARY KEY AUTO_INCREMENT,
    empleado_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    parentesco ENUM('Cónyuge', 'Hijo', 'Hija', 'Padre', 'Madre', 'Hermano', 'Hermana', 'Abuelo', 'Abuela') NOT NULL,
    fecha_nacimiento DATE,
    cedula VARCHAR(20),
    convive ENUM('Sí', 'No') DEFAULT 'Sí',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleado(id) ON DELETE CASCADE
);

-- 12. VEHÍCULO
CREATE TABLE vehiculo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    empleado_id INT NOT NULL,
    posee ENUM('Sí', 'No') NOT NULL,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    anio INT,
    color VARCHAR(30),
    placa VARCHAR(10),
    tipo ENUM('Automóvil', 'Camioneta', 'Moto', 'Camioneta 4x4') DEFAULT 'Automóvil',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleado(id) ON DELETE CASCADE
);

-- 13. EDUCACIÓN
CREATE TABLE educacion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    empleado_id INT NOT NULL,
    nivel ENUM('Básica', 'Media', 'Técnica', 'Universitaria', 'Especialización', 'Maestría', 'Doctorado') NOT NULL,
    institucion VARCHAR(200) NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    fecha_inicio DATE,
    fecha_fin DATE,
    anios INT,
    estatus ENUM('Completado', 'En curso', 'Incompleto') DEFAULT 'Completado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleado(id) ON DELETE CASCADE
);

-- 14. FORMACIÓN (Cursos)
CREATE TABLE formacion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    empleado_id INT NOT NULL,
    curso VARCHAR(200) NOT NULL,
    institucion VARCHAR(200) NOT NULL,
    duracion INT,
    fecha DATE,
    certificado ENUM('Sí', 'No', 'En trámite') DEFAULT 'Sí',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleado(id) ON DELETE CASCADE
);

-- ============================================
-- TABLAS DE PROCESOS
-- ============================================

-- 15. RECLUTAMIENTO (Hoja de Vida)
CREATE TABLE reclutamiento (
    id INT PRIMARY KEY AUTO_INCREMENT,
    primer_apellido VARCHAR(50) NOT NULL,
    segundo_apellido VARCHAR(50) NOT NULL,
    nombres VARCHAR(50) NOT NULL,
    nacionalidad VARCHAR(50),
    cedula VARCHAR(20) UNIQUE NOT NULL,
    estado_civil VARCHAR(20),
    sexo VARCHAR(10),
    fecha_nacimiento DATE,
    edad INT,
    pais_nacimiento VARCHAR(50),
    estado_nacimiento VARCHAR(50),
    ciudad_nacimiento VARCHAR(50),
    direccion VARCHAR(200),
    telefono VARCHAR(20),
    -- Datos familiares
    conyuge_nombre VARCHAR(100),
    conyuge_ocupacion VARCHAR(100),
    conyuge_nacionalidad VARCHAR(50),
    num_hijos INT DEFAULT 0,
    -- Educación
    estudio1_nivel VARCHAR(50),
    estudio1_especialidad VARCHAR(100),
    estudio1_institucion VARCHAR(200),
    estudio1_lugar VARCHAR(100),
    estudio1_titulo VARCHAR(100),
    estudio2_nivel VARCHAR(50),
    estudio2_especialidad VARCHAR(100),
    estudio2_institucion VARCHAR(200),
    estudio2_lugar VARCHAR(100),
    estudio2_titulo VARCHAR(100),
    -- Experiencia
    experiencia1_empresa VARCHAR(200),
    experiencia1_cargo VARCHAR(100),
    experiencia2_empresa VARCHAR(200),
    experiencia2_cargo VARCHAR(100),
    -- Referencias
    referencia1_nombre VARCHAR(100),
    referencia1_telefono VARCHAR(20),
    referencia2_nombre VARCHAR(100),
    referencia2_telefono VARCHAR(20),
    referencia3_nombre VARCHAR(100),
    referencia3_telefono VARCHAR(20),
    -- Socioeconómico
    tipo_vivienda VARCHAR(50),
    tenencia_vivienda VARCHAR(50),
    tipo_trabajador VARCHAR(50),
    -- Documentos
    doc_sintesis BOOLEAN DEFAULT FALSE,
    doc_cedula BOOLEAN DEFAULT FALSE,
    doc_titulos BOOLEAN DEFAULT FALSE,
    doc_constancias BOOLEAN DEFAULT FALSE,
    doc_certificados BOOLEAN DEFAULT FALSE,
    -- Estado
    estado ENUM('En revisión', 'Entrevista RH', 'Entrevista Técnica', 'Prueba Técnica', 'Oferta Laboral', 'Contratado', 'Rechazado') DEFAULT 'En revisión',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 16. PERMISOS
CREATE TABLE permisos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    empleado_nombre VARCHAR(100) NOT NULL,
    empleado_cedula VARCHAR(20) NOT NULL,
    empleado_fecha_ingreso DATE,
    empleado_cargo VARCHAR(100),
    empleado_dependencia VARCHAR(100),
    -- Permisos seleccionados (guardar como JSON o campos separados)
    permisos_seleccionados JSON,
    observaciones TEXT,
    fecha_desde DATE,
    fecha_hasta DATE,
    total_dias INT,
    estado ENUM('Pendiente', 'Aprobado', 'Rechazado') DEFAULT 'Pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 17. VACACIONES
CREATE TABLE vacaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    empleado_nombre VARCHAR(100) NOT NULL,
    empleado_cedula VARCHAR(20) NOT NULL,
    empleado_cargo VARCHAR(100) NOT NULL,
    fecha_ingreso DATE NOT NULL,
    fecha_antiguedad DATE NOT NULL,
    anios_servicio INT DEFAULT 0,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    fecha_reintegro DATE NOT NULL,
    dias_disfrutar INT NOT NULL,
    observaciones TEXT,
    estado ENUM('Pendiente', 'Aprobado', 'Disfrutado', 'Rechazado') DEFAULT 'Pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 18. REPOSOS
CREATE TABLE reposos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    empleado_nombre VARCHAR(100) NOT NULL,
    empleado_cedula VARCHAR(20) NOT NULL,
    empleado_cargo VARCHAR(100) NOT NULL,
    empleado_departamento VARCHAR(100) NOT NULL,
    tipo_reposo VARCHAR(50) NOT NULL,
    numero_reposo VARCHAR(20) NOT NULL,
    diagnostico TEXT NOT NULL,
    fecha_emision DATE NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    fecha_reintegro DATE NOT NULL,
    medico_nombre VARCHAR(100) NOT NULL,
    medico_colegiatura VARCHAR(20) NOT NULL,
    medico_institucion VARCHAR(200) NOT NULL,
    estado ENUM('Activo', 'Completado', 'Vencido', 'En trámite') DEFAULT 'Activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 19. ASISTENCIA
CREATE TABLE asistencia (
    id INT PRIMARY KEY AUTO_INCREMENT,
    mes VARCHAR(20) NOT NULL,
    anio INT NOT NULL,
    empleado1_nombre VARCHAR(100) NOT NULL,
    empleado1_cedula VARCHAR(20) NOT NULL,
    empleado2_nombre VARCHAR(100) NOT NULL,
    empleado2_cedula VARCHAR(20) NOT NULL,
    empleado3_nombre VARCHAR(100) NOT NULL,
    empleado3_cedula VARCHAR(20) NOT NULL,
    -- Registro semanal (guardar como JSON)
    registro_semanal JSON,
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);