-- =============================================================================
-- SCRIPT DE INICIALIZACIÓN DE BASE DE DATOS: SIP-POSTGRADO UNEFA
-- Compatibilidad: PostgreSQL 16+
-- =============================================================================

-- -----------------------------------------------------------------------------
-- 1. Tablas de Catálogos Independientes
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS public.estados (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS public.municipios (
    id SERIAL PRIMARY KEY,
    estado_id INTEGER NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    CONSTRAINT fk_municipio_estado FOREIGN KEY (estado_id) 
        REFERENCES public.estados(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS public.parroquias (
    id SERIAL PRIMARY KEY,
    municipio_id INTEGER NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    CONSTRAINT fk_parroquia_municipio FOREIGN KEY (municipio_id) 
        REFERENCES public.municipios(id) ON DELETE CASCADE
);

-- -----------------------------------------------------------------------------
-- 2. CAPA DE IDENTIDAD (Tabla Maestra de Credenciales)
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS public.usuario_aspirante (
    id SERIAL PRIMARY KEY,
    cedula VARCHAR(20) NOT NULL,
    tipo_cedula VARCHAR(2) NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    CONSTRAINT uk_usuario_cedula UNIQUE (tipo_cedula, cedula)
);

-- -----------------------------------------------------------------------------
-- 3. CAPA DE PERFILES RELACIONALES (Estructura de Extensión 1:1)
-- -----------------------------------------------------------------------------

-- Dirección de Habitación del Aspirante
CREATE TABLE IF NOT EXISTS public.direccion_habitacion (
    id SERIAL PRIMARY KEY,
    usuario_aspirante_id INTEGER NOT NULL UNIQUE,
    parroquia_id INTEGER NOT NULL,
    ciudad_pueblo VARCHAR(100) NOT NULL,
    avenida_calle_vereda VARCHAR(150) NOT NULL,
    urbanizacion_barrio_sector VARCHAR(150) NOT NULL,
    tipo_residencia VARCHAR(50) NOT NULL,
    residencia_detalles TEXT,
    CONSTRAINT fk_direccion_usuario FOREIGN KEY (usuario_aspirante_id) 
        REFERENCES public.usuario_aspirante(id) ON DELETE CASCADE,
    CONSTRAINT fk_direccion_parroquia FOREIGN KEY (parroquia_id) 
        REFERENCES public.parroquias(id)
);

-- Información de Contacto y Entorno Digital
CREATE TABLE IF NOT EXISTS public.contacto_aspirante (
    id SERIAL PRIMARY KEY,
    usuario_aspirante_id INTEGER NOT NULL UNIQUE,
    telefono_fijo VARCHAR(20) NOT NULL,
    celular VARCHAR(20) NOT NULL,
    twitter VARCHAR(100),
    facebook VARCHAR(100),
    instagram VARCHAR(100),
    linkedin VARCHAR(100),
    condicion_ingreso VARCHAR(50) NOT NULL,
    condicion_usuario VARCHAR(50) NOT NULL,
    CONSTRAINT fk_contacto_usuario FOREIGN KEY (usuario_aspirante_id) 
        REFERENCES public.usuario_aspirante(id) ON DELETE CASCADE
);

-- Datos Académicos y Registro Laboral
CREATE TABLE IF NOT EXISTS public.academicos_laborales_aspirante (
    id SERIAL PRIMARY KEY,
    usuario_aspirante_id INTEGER NOT NULL UNIQUE,
    area_conocimiento VARCHAR(150) NOT NULL,
    nivel_academico VARCHAR(100) NOT NULL,
    universidad VARCHAR(200) NOT NULL,
    titulo_obtenido VARCHAR(200) NOT NULL,
    ano_graduacion INTEGER NOT NULL,
    promedio NUMERIC(4,2) NOT NULL,
    tipo_institucion VARCHAR(100) NOT NULL,
    nombre_institucion VARCHAR(150) NOT NULL,
    cargo VARCHAR(150) NOT NULL,
    antiguedad VARCHAR(50) NOT NULL,
    telefono_trabajo VARCHAR(20),
    trabaja_unefa VARCHAR(2) NOT NULL,
    CONSTRAINT fk_academicos_usuario FOREIGN KEY (usuario_aspirante_id) 
        REFERENCES public.usuario_aspirante(id) ON DELETE CASCADE
);

-- Documentos de Respaldo y Propuesta de Investigación
CREATE TABLE IF NOT EXISTS public.documentos_interes_aspirante (
    id SERIAL PRIMARY KEY,
    usuario_aspirante_id INTEGER NOT NULL UNIQUE,
    tema_interes TEXT NOT NULL,
    cuenta_con_beca VARCHAR(50) NOT NULL,
    fecha_ingreso_unefa DATE NOT NULL,
    ruta_documento_identidad VARCHAR(255) NOT NULL,
    ruta_titulo VARCHAR(255) NOT NULL,
    CONSTRAINT fk_documentos_usuario FOREIGN KEY (usuario_aspirante_id) 
        REFERENCES public.usuario_aspirante(id) ON DELETE CASCADE
);

-- Catálogo Base de Preguntas del Instrumento (Baremo)
CREATE TABLE IF NOT EXISTS public.baremo_preguntas (
    id SERIAL PRIMARY KEY,
    categoria VARCHAR(100) NOT NULL,
    enunciado TEXT NOT NULL,
    puntaje_maximo NUMERIC(4,2) NOT NULL
);

-- -----------------------------------------------------------------------------
-- 4. CAPA DE DOCUMENTACIÓN INTEGRADA (Comentarios en Base de Datos)
-- -----------------------------------------------------------------------------

COMMENT ON TABLE public.usuario_aspirante IS 'Tabla núcleo de control de credenciales e identidad de los aspirantes de postgrado.';
COMMENT ON TABLE public.documentos_interes_aspirante IS 'Custodia la delimitación de la línea de investigación del aspirante y las rutas del almacenamiento local hacia sus soportes digitales.';
COMMENT ON COLUMN public.documentos_interes_aspirante.tema_interes IS 'Discurso sintético de la propuesta de investigación vinculada a las áreas prioritarias del país.';
COMMENT ON COLUMN public.documentos_interes_aspirante.ruta_documento_identidad IS 'Ruta de acceso relativo al archivo de imagen o PDF de la Cédula de Identidad en el servidor local.';
COMMENT ON COLUMN public.documentos_interes_aspirante.ruta_titulo IS 'Ruta de acceso relativo al archivo de imagen o PDF del Título Universitario de Pregrado.';
COMMENT ON TABLE public.academicos_laborales_aspirante IS 'Registra el expediente curricular y estatus laboral del profesional necesario para auditorías del comité académico.';
COMMENT ON COLUMN public.academicos_laborales_aspirante.promedio IS 'Calificación acumulada del expediente de pregrado registrada bajo escala exacta de dos decimales.';