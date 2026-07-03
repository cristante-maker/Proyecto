-- ============================================
-- SISTEMA DE GESTIÓN RRHH - BASE DE DATOS (CORREGIDA SEGÚN MER)
-- ============================================

CREATE DATABASE IF NOT EXISTS db_system;
USE db_system;

-- ============================================
-- 1. TABLAS INDEPENDIENTES (CATÁLOGOS BASE)
-- ============================================

CREATE TABLE pais (
    id_pais INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) NOT NULL
);

CREATE TABLE estado (
    id_estado INT PRIMARY KEY AUTO_INCREMENT,
    id_pais INT NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_pais) REFERENCES pais(id_pais) ON DELETE CASCADE
);

CREATE TABLE ciudad (
    id_ciudad INT PRIMARY KEY AUTO_INCREMENT,
    id_estado INT NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    Codigo_Postal VARCHAR(20),
    FOREIGN KEY (id_estado) REFERENCES estado(id_estado) ON DELETE CASCADE
);

CREATE TABLE tipo_institucion (
    id_tipo_inst INT PRIMARY KEY AUTO_INCREMENT,
    Tipo_Institucion VARCHAR(100) NOT NULL
);

CREATE TABLE institucion (
    id_institucion INT PRIMARY KEY AUTO_INCREMENT,
    id_tipo_inst INT NOT NULL,
    Nombre VARCHAR(200) NOT NULL,
    Institucion_Tipo VARCHAR(100),
    Ubicacion VARCHAR(200),
    Dependencia VARCHAR(100),
    Telefono VARCHAR(20),
    FOREIGN KEY (id_tipo_inst) REFERENCES tipo_institucion(id_tipo_inst) ON DELETE CASCADE
);

CREATE TABLE idioma (
    id_idioma INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50) NOT NULL
);

CREATE TABLE tipo_vivienda (
    id_tip_vivienda INT PRIMARY KEY AUTO_INCREMENT,
    Denominacion VARCHAR(100) NOT NULL
);

CREATE TABLE tenencia_vivienda (
    id_tenencia INT PRIMARY KEY AUTO_INCREMENT,
    id_tip_vivienda INT NOT NULL,
    Denominacion VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_tip_vivienda) REFERENCES tipo_vivienda(id_tip_vivienda) ON DELETE CASCADE
);

CREATE TABLE tipo_permiso (
    id_tipo_perm INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) NOT NULL
);

-- ============================================
-- 2. TABLA PRINCIPAL: EMPLEADO
-- ============================================

CREATE TABLE empleado (
    Cedula VARCHAR(20) PRIMARY KEY,
    id_ciudad INT NOT NULL,
    Primer_apellido VARCHAR(50) NOT NULL,
    Segundo_apellido VARCHAR(50),
    Primer_Nombre VARCHAR(50) NOT NULL,
    Segundo_Nombre VARCHAR(50),
    Nacionalidad VARCHAR(50),
    Sexo VARCHAR(20),
    Fecha_Nac DATE,
    Estado_Civil VARCHAR(30),
    Edad INT,
    Codigo_postal VARCHAR(20),
    Num_Telefonico VARCHAR(20),
    Estatura DECIMAL(4,2),
    Peso DECIMAL(5,2),
    Grp_Sanguineo VARCHAR(10),
    Hab_manual VARCHAR(50),
    Numeros_hijos INT DEFAULT 0,
    FOREIGN KEY (id_ciudad) REFERENCES ciudad(id_ciudad) ON DELETE RESTRICT
);

-- ============================================
-- 3. TABLAS RELACIONADAS DIRECTAMENTE AL EMPLEADO
-- ============================================

CREATE TABLE extranjero (
    Ced_Empleado VARCHAR(20) PRIMARY KEY,
    Num_pasaporte VARCHAR(50) NOT NULL,
    Tipo_Visa VARCHAR(50),
    Tiempo_estadia VARCHAR(50),
    FOREIGN KEY (Ced_Empleado) REFERENCES empleado(Cedula) ON DELETE CASCADE
);

CREATE TABLE nacionalizacion (
    Ced_Empleado VARCHAR(20) PRIMARY KEY,
    Numero_gaceta VARCHAR(50) NOT NULL,
    Fecha_gaceta DATE,
    Pais_procedente VARCHAR(100),
    FOREIGN KEY (Ced_Empleado) REFERENCES empleado(Cedula) ON DELETE CASCADE
);

CREATE TABLE vehiculo (
    id_vehiculo INT PRIMARY KEY AUTO_INCREMENT,
    Cedula_Emp VARCHAR(20) NOT NULL,
    Posee_Vehiculo VARCHAR(2) NOT NULL,
    Marca VARCHAR(50),
    Modelo VARCHAR(50),
    Numero_placa VARCHAR(20),
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE
);

CREATE TABLE familia (
    id_familiar INT PRIMARY KEY AUTO_INCREMENT,
    Cedula_Emp VARCHAR(20) NOT NULL,
    Numero_Cedula VARCHAR(20),
    Nombre_y_Ap VARCHAR(150) NOT NULL,
    Parentesco VARCHAR(50) NOT NULL,
    Sexo VARCHAR(20),
    Fecha_Nac DATE,
    Nivel_educativo VARCHAR(100),
    Convec_Colectiva VARCHAR(100),
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE
);

CREATE TABLE documentos (
    id_documento INT PRIMARY KEY AUTO_INCREMENT,
    Cedula_Emp VARCHAR(20) NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE
);

CREATE TABLE licencia (
    id_licencia INT PRIMARY KEY AUTO_INCREMENT,
    Cedula_Emp VARCHAR(20) NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    Tipo_licencia VARCHAR(50),
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE
);

CREATE TABLE asistencia (
    id_asistencia INT PRIMARY KEY AUTO_INCREMENT,
    Cedula_Emp VARCHAR(20) NOT NULL,
    Fecha DATE NOT NULL,
    Hora_Entrada TIME,
    Hora_Salida TIME,
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE
);

CREATE TABLE dominio (
    id_dominio INT PRIMARY KEY AUTO_INCREMENT,
    id_idioma INT NOT NULL,
    Cedula_Emp VARCHAR(20) NOT NULL, 
    Comprende VARCHAR(2),
    Habla VARCHAR(2),
    Lee VARCHAR(2),
    Escribe VARCHAR(2),
    FOREIGN KEY (id_idioma) REFERENCES idioma(id_idioma) ON DELETE CASCADE,
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE
);

CREATE TABLE vivienda (
    id_vivienda INT PRIMARY KEY AUTO_INCREMENT,
    Cedula_Emp VARCHAR(20) NOT NULL,
    id_tip_vivienda INT NOT NULL,
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE,
    FOREIGN KEY (id_tip_vivienda) REFERENCES tipo_vivienda(id_tip_vivienda) ON DELETE CASCADE
);

CREATE TABLE experiencia_laboral (
    id_experiencia INT PRIMARY KEY AUTO_INCREMENT,
    Cedula_Emp VARCHAR(20) NOT NULL,
    Cargo_inicial VARCHAR(100),
    Fecha_ingreso DATE,
    Fecha_egreso DATE,
    Sueldo_Inicial DECIMAL(10,2),
    Sueldo_Final DECIMAL(10,2),
    Motivo_retiro VARCHAR(200),
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE
);

CREATE TABLE educacion (
    id_educacion INT PRIMARY KEY AUTO_INCREMENT,
    Cedula_Emp VARCHAR(20) NOT NULL,
    id_institucion INT NOT NULL,
    Nivel_Instruccion VARCHAR(100),
    Lugar VARCHAR(100),
    Desde_anos VARCHAR(4),
    Hasta_anos VARCHAR(4),
    Anos_Cursados INT,
    Titulo_Obtenido VARCHAR(150),
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE,
    FOREIGN KEY (id_institucion) REFERENCES institucion(id_institucion) ON DELETE CASCADE
);

CREATE TABLE formacion (
    id_formacion INT PRIMARY KEY AUTO_INCREMENT,
    Cedula_Emp VARCHAR(20) NOT NULL,
    id_institucion INT NOT NULL,
    Denominacion VARCHAR(150),
    Localidad VARCHAR(100),
    Duracion_horas INT,
    Fecha DATE,
    Titulo_Certif VARCHAR(150),
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE,
    FOREIGN KEY (id_institucion) REFERENCES institucion(id_institucion) ON DELETE CASCADE
);

-- ============================================
-- 4. RAMA DE SOLICITUDES (VACACIONES, PERMISOS, ETC)
-- ============================================

CREATE TABLE solicitud (
    id_solicitud INT PRIMARY KEY AUTO_INCREMENT,
    Cedula_Emp VARCHAR(20) NOT NULL,
    Nombre VARCHAR(100),
    Tipo_Solicitud VARCHAR(50),
    Fecha_Solicitud DATE,
    FOREIGN KEY (Cedula_Emp) REFERENCES empleado(Cedula) ON DELETE CASCADE
);

CREATE TABLE vacaciones (
    id_vacaciones INT PRIMARY KEY AUTO_INCREMENT,
    id_solicitud INT NOT NULL,
    Fecha_inicio DATE,
    Fecha_Culminacion DATE,
    Fecha_Reingreso DATE,
    Fecha_Rendimiento DATE,
    FOREIGN KEY (id_solicitud) REFERENCES solicitud(id_solicitud) ON DELETE CASCADE
);

CREATE TABLE pendiente (
    id_pendiente INT PRIMARY KEY AUTO_INCREMENT,
    id_solicitud INT NOT NULL,
    Nombre VARCHAR(100),
    Dias_a_disfrutar INT,
    FOREIGN KEY (id_solicitud) REFERENCES solicitud(id_solicitud) ON DELETE CASCADE
);

CREATE TABLE permiso (
    id_permiso INT PRIMARY KEY AUTO_INCREMENT,
    id_solicitud INT NOT NULL,
    id_tipo_perm INT NOT NULL,
    Nombre VARCHAR(100),
    FOREIGN KEY (id_solicitud) REFERENCES solicitud(id_solicitud) ON DELETE CASCADE,
    FOREIGN KEY (id_tipo_perm) REFERENCES tipo_permiso(id_tipo_perm) ON DELETE CASCADE
);

CREATE TABLE motivo (
    id_motivo INT PRIMARY KEY AUTO_INCREMENT,
    id_permiso INT NOT NULL,
    Nombre VARCHAR(100),
    FOREIGN KEY (id_permiso) REFERENCES permiso(id_permiso) ON DELETE CASCADE
);

CREATE TABLE articulo (
    id_articulo INT PRIMARY KEY AUTO_INCREMENT,
    id_motivo INT NOT NULL,
    Nombre VARCHAR(100),
    FOREIGN KEY (id_motivo) REFERENCES motivo(id_motivo) ON DELETE CASCADE
);

CREATE TABLE soporte (
    id_soporte INT PRIMARY KEY AUTO_INCREMENT,
    id_motivo INT NOT NULL,
    Nombre VARCHAR(100),
    FOREIGN KEY (id_motivo) REFERENCES motivo(id_motivo) ON DELETE CASCADE
);

CREATE TABLE base_legal (
    id_base_legal INT PRIMARY KEY AUTO_INCREMENT,
    id_motivo INT NOT NULL,
    Nombre VARCHAR(100),
    FOREIGN KEY (id_motivo) REFERENCES motivo(id_motivo) ON DELETE CASCADE
);
