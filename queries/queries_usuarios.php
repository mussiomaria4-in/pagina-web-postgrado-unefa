<?php
/**
 * Consultas a la tabla usuario_aspirante y tablas relacionales del perfil.
 * - Compatibilidad estricta con PHP 5.6.
 * - Soporte para PostgreSQL (Cláusulas ON CONFLICT).
 */

if (!function_exists('query_usuario_por_id')) {
    /**
     * Busca los datos básicos de la cuenta del aspirante.
     * * @param PDO   $pdo
     * @param int   $id
     * @return array|false fila asociativa o false si no existe
     */
    function query_usuario_por_id($pdo, $id)
    {
        $stmt = $pdo->prepare(
            'SELECT id, cedula, tipo_cedula, email, nombres, apellidos FROM usuario_aspirante WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ? $fila : false;
    }
}

if (!function_exists('query_insertar_usuario_registro')) {
    /**
     * PASO 1 (Registro Inicial): Inserta la cuenta básica del aspirante.
     *
     * @param PDO   $pdo
     * @param array $params claves: cedula_limpia, tipo, nombres, apellidos, email, password_hash
     * @return bool true si se ejecutó correctamente
     */
    function query_insertar_usuario_registro($pdo, $params)
    {
        $sql = 'INSERT INTO usuario_aspirante (cedula, tipo_cedula, nombres, apellidos, email, password, telefono, direccion) 
                VALUES (:ci, :tipo, :nom, :ape, :mail, :pass, :tel, :dir)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':ci'   => $params['cedula_limpia'],
            ':tipo' => $params['tipo'],
            ':nom'  => $params['nombres'],
            ':ape'  => $params['apellidos'],
            ':mail' => $params['email'],
            ':pass' => $params['password_hash'],
            ':tel'  => null,
            ':dir'  => 'Pendiente', // Se mantiene por compatibilidad si la tabla base aún pide el campo
        ]);
        return true;
    }
}

if (!function_exists('query_actualizar_perfil_usuario_base')) {
    /**
     * Actualiza los datos de identidad base del aspirante en la tabla principal.
     *
     * @param PDO   $pdo
     * @param int   $userId
     * @param array $params claves: nombres, apellidos, telefono
     */
    function query_actualizar_perfil_usuario_base($pdo, $userId, $params)
    {
        $sql = 'UPDATE usuario_aspirante SET
                    nombres = :nom,
                    apellidos = :ape,
                    telefono = :tel
                WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $params['nombres'],
            ':ape' => $params['apellidos'],
            ':tel' => $params['telefono'], // Celular principal
            ':id'  => $userId,
        ]);
    }
}

if (!function_exists('query_obtener_o_crear_parroquia_id')) {
    /**
     * Resuelve parroquia_id a partir de los campos del formulario (parroquiaHabitacion, municipioHabitacion, estadoHabitacion).
     * Si parroquiaHabitacion es numérico, se usa como ID. Si no, busca o crea estado/municipio/parroquia por nombre.
     *
     * @param PDO   $pdo
     * @param array $datos claves del POST: parroquiaHabitacion, municipioHabitacion, estadoHabitacion
     * @return int
     */
    function query_obtener_o_crear_parroquia_id($pdo, $datos)
    {
        $parroquia_raw = trim((string) (isset($datos['parroquiaHabitacion']) ? $datos['parroquiaHabitacion'] : ''));
        if ($parroquia_raw !== '' && ctype_digit($parroquia_raw)) {
            return (int) $parroquia_raw;
        }

        $estado = trim((string) (isset($datos['estadoHabitacion']) ? $datos['estadoHabitacion'] : ''));
        $municipio = trim((string) (isset($datos['municipioHabitacion']) ? $datos['municipioHabitacion'] : ''));
        $parroquia_nombre = $parroquia_raw !== '' ? $parroquia_raw : ($municipio !== '' ? $municipio : 'Sin especificar');

        if ($estado === '') {
            $estado = 'Sin especificar';
        }
        if ($municipio === '') {
            $municipio = 'Sin especificar';
        }

        $stmt = $pdo->prepare('SELECT id FROM estados WHERE nombre = :nombre LIMIT 1');
        $stmt->execute(array(':nombre' => $estado));
        $estado_id = $stmt->fetchColumn();
        if (!$estado_id) {
            $stmt = $pdo->prepare('INSERT INTO estados (nombre) VALUES (:nombre) RETURNING id');
            $stmt->execute(array(':nombre' => $estado));
            $estado_id = $stmt->fetchColumn();
        }

        $stmt = $pdo->prepare(
            'SELECT id FROM municipios WHERE nombre = :nombre AND estado_id = :estado_id LIMIT 1'
        );
        $stmt->execute(array(':nombre' => $municipio, ':estado_id' => $estado_id));
        $municipio_id = $stmt->fetchColumn();
        if (!$municipio_id) {
            $stmt = $pdo->prepare(
                'INSERT INTO municipios (estado_id, nombre) VALUES (:estado_id, :nombre) RETURNING id'
            );
            $stmt->execute(array(':estado_id' => $estado_id, ':nombre' => $municipio));
            $municipio_id = $stmt->fetchColumn();
        }

        $stmt = $pdo->prepare(
            'SELECT id FROM parroquias WHERE nombre = :nombre AND municipio_id = :municipio_id LIMIT 1'
        );
        $stmt->execute(array(':nombre' => $parroquia_nombre, ':municipio_id' => $municipio_id));
        $parroquia_id = $stmt->fetchColumn();
        if (!$parroquia_id) {
            $stmt = $pdo->prepare(
                'INSERT INTO parroquias (municipio_id, nombre) VALUES (:municipio_id, :nombre) RETURNING id'
            );
            $stmt->execute(array(':municipio_id' => $municipio_id, ':nombre' => $parroquia_nombre));
            $parroquia_id = $stmt->fetchColumn();
        }

        return (int) $parroquia_id;
    }
}

if (!function_exists('query_guardar_direccion')) {
    /**
     * PASO 2: Registra o actualiza la Dirección de Habitación en PostgreSQL.
     *
     * @param PDO   $pdo
     * @param int   $userId
     * @param array $params claves: parroquia_id, ciudad_pueblo, avenida_calle_vereda, urbanizacion_barrio_sector, tipo_residencia, residencia_detalles
     */
    function query_guardar_direccion($pdo, $userId, $params)
    {
        $sql = 'INSERT INTO public.direccion_habitacion (
                    usuario_aspirante_id, parroquia_id, ciudad_pueblo, 
                    avenida_calle_vereda, urbanizacion_barrio_sector, 
                    tipo_residencia, residencia_detalles
                ) VALUES (:user_id, :parroquia, :ciudad, :calle, :sector, :tipo, :detalles)
                ON CONFLICT (usuario_aspirante_id) 
                DO UPDATE SET 
                    parroquia_id = EXCLUDED.parroquia_id,
                    ciudad_pueblo = EXCLUDED.ciudad_pueblo,
                    avenida_calle_vereda = EXCLUDED.avenida_calle_vereda,
                    urbanizacion_barrio_sector = EXCLUDED.urbanizacion_barrio_sector,
                    tipo_residencia = EXCLUDED.tipo_residencia,
                    residencia_detalles = EXCLUDED.residencia_detalles';
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id'   => $userId,
            ':parroquia' => (int) $params['parroquia_id'],
            ':ciudad'    => $params['ciudad_pueblo'],
            ':calle'     => $params['avenida_calle_vereda'],
            ':sector'    => !empty($params['urbanizacion_barrio_sector']) ? $params['urbanizacion_barrio_sector'] : null,
            ':tipo'      => $params['tipo_residencia'],
            ':detalles'  => isset($params['residencia_detalles']) ? (string) $params['residencia_detalles'] : ''
        ]);
    }
}

if (!function_exists('query_guardar_contacto')) {
    /**
     * PASO 3: Registra o actualiza Redes Sociales, Contacto y Condiciones del Aspirante.
     *
     * @param PDO   $pdo
     * @param int   $userId
     * @param array $params claves: twitter, facebook, instagram, linkedin, telefono_fijo, celular, condicion_ingreso, condicion_usuario
     */
    function query_guardar_contacto($pdo, $userId, $params)
    {
        $sql = 'INSERT INTO public.contacto_aspirante (
                    usuario_aspirante_id, twitter, facebook, instagram, linkedin, 
                    telefono_fijo, celular, condicion_ingreso, condicion_usuario
                ) VALUES (:user_id, :tw, :fb, :ig, :li, :fijo, :cel, :ingreso, :usuario)
                ON CONFLICT (usuario_aspirante_id) 
                DO UPDATE SET 
                    twitter = EXCLUDED.twitter,
                    facebook = EXCLUDED.facebook,
                    instagram = EXCLUDED.instagram,
                    linkedin = EXCLUDED.linkedin,
                    telefono_fijo = EXCLUDED.telefono_fijo,
                    celular = EXCLUDED.celular,
                    condicion_ingreso = EXCLUDED.condicion_ingreso,
                    condicion_usuario = EXCLUDED.condicion_usuario';
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':tw'      => !empty($params['twitter']) ? $params['twitter'] : null,
            ':fb'      => !empty($params['facebook']) ? $params['facebook'] : null,
            ':ig'      => !empty($params['instagram']) ? $params['instagram'] : null,
            ':li'      => !empty($params['linkedin']) ? $params['linkedin'] : null,
            ':fijo'    => $params['telefono_fijo'],
            ':cel'     => $params['celular'],
            ':ingreso' => $params['condicion_ingreso'],
            ':usuario' => $params['condicion_usuario']
        ]);
    }
}

if (!function_exists('query_guardar_academicos_laborales')) {
    /**
     * PASO 4: Registra o actualiza el Historial Académico y Laboral (Incluye nota del Baremo).
     *
     * @param PDO   $pdo
     * @param int   $userId
     * @param array $params claves: area_conocimiento, nivel_academico, universidad, titulo_obtenido, ano_graduacion, promedio, tipo_institucion, nombre_institucion, antiguedad, telefono_trabajo, cargo, trabaja_unefa
     */
    function query_guardar_academicos_laborales($pdo, $userId, $params)
    {
        $sql = 'INSERT INTO public.academicos_laborales_aspirante (
                    usuario_aspirante_id, area_conocimiento, nivel_academico, universidad, 
                    titulo_obtenido, ano_graduacion, promedio, tipo_institucion, 
                    nombre_institucion, antiguedad, telefono_trabajo, cargo, trabaja_unefa
                ) VALUES (:user_id, :area, :nivel, :univ, :titulo, :ano, :prom, :tipo_inst, :nom_inst, :anti, :tel_trab, :cargo, :unefa)
                ON CONFLICT (usuario_aspirante_id) 
                DO UPDATE SET 
                    area_conocimiento = EXCLUDED.area_conocimiento,
                    nivel_academico = EXCLUDED.nivel_academico,
                    universidad = EXCLUDED.universidad,
                    titulo_obtenido = EXCLUDED.titulo_obtenido,
                    ano_graduacion = EXCLUDED.ano_graduacion,
                    promedio = EXCLUDED.promedio,
                    tipo_institucion = EXCLUDED.tipo_institucion,
                    nombre_institucion = EXCLUDED.nombre_institucion,
                    antiguedad = EXCLUDED.antiguedad,
                    telefono_trabajo = EXCLUDED.telefono_trabajo,
                    cargo = EXCLUDED.cargo,
                    trabaja_unefa = EXCLUDED.trabaja_unefa';
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id'   => $userId,
            ':area'      => $params['area_conocimiento'],
            ':nivel'     => $params['nivel_academico'],
            ':univ'      => $params['universidad'],
            ':titulo'    => $params['titulo_obtenido'],
            ':ano'       => (int) $params['ano_graduacion'],
            ':prom'      => floatval($params['promedio']), // Mantiene la precisión numérica en Postgres
            ':tipo_inst' => $params['tipo_institucion'],
            ':nom_inst'  => $params['nombre_institucion'],
            ':anti'      => $params['antiguedad'],
            ':tel_trab'  => !empty($params['telefono_trabajo']) ? $params['telefono_trabajo'] : null,
            ':cargo'     => $params['cargo'],
            ':unefa'     => $params['trabaja_unefa']
        ]);
    }
}

if (!function_exists('query_guardar_finalizacion_paso5')) {
    /**
     * PASO 5: Registra los datos de interés investigativo, becas y las rutas de los documentos subidos.
     *
     * @param PDO   $pdo
     * @param int   $userId
     * @param array $params claves: tema_interes, cuenta_con_beca, fecha_ingreso_unefa, ruta_ci, ruta_titulo
     */
    function query_guardar_finalizacion_paso5($pdo, $userId, $params)
    {
        $sql = 'INSERT INTO public.documentos_interes_aspirante (
                    usuario_aspirante_id, tema_interes, cuenta_con_beca, 
                    fecha_ingreso_unefa, ruta_documento_identidad, ruta_titulo
                ) VALUES (:user_id, :tema, :beca, :fecha, :ruta_ci, :ruta_titulo)
                ON CONFLICT (usuario_aspirante_id) 
                DO UPDATE SET 
                    tema_interes = EXCLUDED.tema_interes,
                    cuenta_con_beca = EXCLUDED.cuenta_con_beca,
                    fecha_ingreso_unefa = EXCLUDED.fecha_ingreso_unefa,
                    ruta_documento_identidad = EXCLUDED.ruta_documento_identidad,
                    ruta_titulo = EXCLUDED.ruta_titulo';
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id'     => $userId,
            ':tema'        => $params['tema_interes'],
            ':beca'        => $params['cuenta_con_beca'],
            ':fecha'       => $params['fecha_ingreso_unefa'],
            ':ruta_ci'     => $params['ruta_ci'],
            ':ruta_titulo' => $params['ruta_titulo']
        ]);
    }
}