<?php

require_once __DIR__ . '/procesar.php';

if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}
if (file_exists(__DIR__ . '/../queries/queries_usuarios.php')) {
    require_once __DIR__ . '/../queries/queries_usuarios.php';
}

if (!function_exists('obtener_valor')) {
    function obtener_valor($array, $clave, $defecto)
    {
        return isset($array[$clave]) ? $array[$clave] : $defecto;
    }
}

if (!function_exists('telefono_trabajo_opcional_valido')) {
    function telefono_trabajo_opcional_valido($valor)
    {
        $valor = trim((string) $valor);
        if ($valor === '') {
            return true;
        }
        return telefono_ve_valido($valor);
    }
}

if (!function_exists('validar_datos_perfil')) {
    function validar_datos_perfil($datos)
    {
        $lista_errores = array();
        $reglas_obligatorias = array(
            'tipoDocumento'     => array('fn' => 'campo_no_vacio', 'msg' => 'Selecciona una opción de tipo de documento'),
            'cedula'            => array('fn' => 'cedula_es_valida', 'msg' => 'Cédula incorrecta'),
            'primerNombre'      => array('fn' => 'campo_no_vacio', 'msg' => 'Por favor ingresa tu primer nombre'),
            'primerApellido'    => array('fn' => 'campo_no_vacio', 'msg' => 'Por favor ingresa tu primer apellido'),
            'segundoApellido'   => array('fn' => 'campo_no_vacio', 'msg' => 'Por favor ingresa tu segundo apellido'),
            'fechaNacimiento'   => array('fn' => 'campo_no_vacio', 'msg' => 'Indica la fecha de nacimiento'),
            'sexo'              => array('fn' => 'campo_no_vacio', 'msg' => 'Selecciona el sexo'),
            'estadoCivil'       => array('fn' => 'campo_no_vacio', 'msg' => 'Selecciona el estado civil'),
            'estadoHabitacion'  => array('fn' => 'campo_no_vacio', 'msg' => 'Selecciona el estado de habitación'),
            'municipioHabitacion' => array('fn' => 'campo_no_vacio', 'msg' => 'Selecciona el municipio'),
            'ciudadHabitacion'  => array('fn' => 'campo_no_vacio', 'msg' => 'Indica la ciudad o pueblo'),
            'avenidaCalle'      => array('fn' => 'campo_no_vacio', 'msg' => 'Indica avenida, calle o vereda'),
            'tipoResidencia'    => array('fn' => 'campo_no_vacio', 'msg' => 'Selecciona el tipo de residencia'),
            'telefono'          => array('fn' => 'telefono_ve_valido', 'msg' => 'Ingresa un teléfono fijo válido (11 dígitos, inicia con 0)'),
            'celular'           => array('fn' => 'telefono_ve_valido', 'msg' => 'Ingresa un celular válido (11 dígitos, inicia con 0)'),
            'condicion'         => array('fn' => 'campo_no_vacio', 'msg' => 'Selecciona la condición de ingreso'),
            'condicionUsuario'  => array('fn' => 'campo_no_vacio', 'msg' => 'Selecciona la condición del usuario'),
            'tipoInstitucion'   => array('fn' => 'campo_no_vacio', 'msg' => 'Selecciona el tipo de institución'),
            'nombreInstitucion' => array('fn' => 'campo_no_vacio', 'msg' => 'Indica el nombre de la institución'),
            'antiguedad'        => array('fn' => 'campo_no_vacio', 'msg' => 'Indica la antigüedad'),
            'telefonoTrabajo'   => array('fn' => 'telefono_trabajo_opcional_valido', 'msg' => 'Teléfono de trabajo inválido'),
            'cargo'             => array('fn' => 'campo_no_vacio', 'msg' => 'Indica el cargo'),
            'trabajaUnefa'      => array('fn' => 'campo_no_vacio', 'msg' => 'Indica si trabaja en la UNEFA'),
            'areaConocimiento'  => array('fn' => 'campo_no_vacio', 'msg' => 'Indica el área de conocimiento'),
            'nivelAcademico'    => array('fn' => 'campo_no_vacio', 'msg' => 'Indica el nivel académico'),
            'universidad'       => array('fn' => 'campo_no_vacio', 'msg' => 'Indica la universidad'),
            'tituloAcademico'   => array('fn' => 'campo_no_vacio', 'msg' => 'Indica el título obtenido'),
            'anoGraduacion'     => array('fn' => 'campo_no_vacio', 'msg' => 'Selecciona el año de graduación'),
            'promedio'          => array('fn' => 'campo_no_vacio', 'msg' => 'Indica el promedio'),
            'temaInvestigacion' => array('fn' => 'campo_no_vacio', 'msg' => 'Describe tu tema de interés para la investigación')
        );

        $reglas_opcionales = array(
            'telefonoTrabajo' => array('fn' => 'telefono_trabajo_opcional_valido', 'msg' => 'Teléfono de trabajo inválido')
        );

        foreach ($reglas_obligatorias as $campo => $regla) {
            $valor = obtener_valor($datos, $campo, '');
            if (!call_user_func($regla['fn'], $valor)) {
                $lista_errores[$campo] = $regla['msg'];
            }
        }

        foreach ($reglas_opcionales as $campo => $regla) {
            if (!array_key_exists($campo, $datos)) {
                continue;
            }
            $valor = obtener_valor($datos, $campo, '');
            if (!call_user_func($regla['fn'], $valor)) {
                $lista_errores[$campo] = $regla['msg'];
            }
        }

        if (!isset($_FILES['archivo_ci']) || !isset($_FILES['archivo_ci']['error']) || $_FILES['archivo_ci']['error'] !== UPLOAD_ERR_OK) {
            $lista_errores['archivo_ci'] = 'Debes adjuntar el Documento de Identidad';
        }
        if (!isset($_FILES['archivo_titulo']) || !isset($_FILES['archivo_titulo']['error']) || $_FILES['archivo_titulo']['error'] !== UPLOAD_ERR_OK) {
            $lista_errores['archivo_titulo'] = 'Debes adjuntar el Título';
        }

        if (obtener_valor($datos, 'tipoDocumento', '') === 'E' && !campo_no_vacio(obtener_valor($datos, 'paisNacimiento', ''))) {
            $lista_errores['paisNacimiento'] = 'Indica el país de nacimiento';
        }
        if (obtener_valor($datos, 'tipoResidencia', '') === 'Apartamento' && !campo_no_vacio(obtener_valor($datos, 'piso', ''))) {
            $lista_errores['piso'] = 'Indica el piso';
        }
        if (obtener_valor($datos, 'tipoResidencia', '') === 'Apartamento' && !campo_no_vacio(obtener_valor($datos, 'apartamento', ''))) {
            $lista_errores['apartamento'] = 'Indica el número de apartamento';
        }

        return $lista_errores;
    }
}

if (!function_exists('armar_residencia_detalles_perfil')) {
    function armar_residencia_detalles_perfil($datos)
    {
        $partes = array();
        $residencia = trim((string) obtener_valor($datos, 'residencia', ''));
        if ($residencia !== '') {
            $partes[] = $residencia;
        }
        $piso = trim((string) obtener_valor($datos, 'piso', ''));
        if ($piso !== '') {
            $partes[] = 'Piso ' . $piso;
        }
        $apartamento = trim((string) obtener_valor($datos, 'apartamento', ''));
        if ($apartamento !== '') {
            $partes[] = 'Apto ' . $apartamento;
        }
        return trim(implode(', ', $partes));
    }
}

if (!function_exists('cuenta_con_beca_desde_datos')) {
    function cuenta_con_beca_desde_datos($datos)
    {
        $valor = trim((string) obtener_valor($datos, 'tipoBeca', ''));
        return $valor !== '' ? $valor : 'No';
    }
}

if (!function_exists('fecha_ingreso_unefa_desde_datos')) {
    function fecha_ingreso_unefa_desde_datos($datos)
    {
        $valor = trim((string) obtener_valor($datos, 'fechaIngresoUnefa', ''));
        return $valor !== '' ? $valor : date('Y-m-d');
    }
}

if (!function_exists('ano_graduacion_desde_datos')) {
    function ano_graduacion_desde_datos($datos)
    {
        $valor = trim((string) obtener_valor($datos, 'anoGraduacion', ''));
        if ($valor === '') {
            return 0;
        }
        if (ctype_digit($valor)) {
            return (int) $valor;
        }
        if (stripos($valor, 'antes') !== false) {
            return 2009;
        }
        $solo_digitos = preg_replace('/\D/', '', $valor);
        return $solo_digitos !== '' ? (int) $solo_digitos : 2009;
    }
}

if (!function_exists('armar_direccion_perfil')) {
    function armar_direccion_perfil($datos)
    {
        $obs = trim((string) obtener_valor($datos, 'observaciones', ''));
        return "--- Datos personales ---\n"
            . 'Fecha de nacimiento: ' . (string) obtener_valor($datos, 'fechaNacimiento', '') . "\n"
            . 'Sexo: ' . (string) obtener_valor($datos, 'sexo', '') . "\n"
            . 'Estado civil: ' . (string) obtener_valor($datos, 'estadoCivil', '') . "\n"
            . 'Condición de ingreso: ' . (string) obtener_valor($datos, 'condicion', '') . "\n\n"
            . "--- Dirección de habitación ---\n"
            . 'Tipo de residencia: ' . (string) obtener_valor($datos, 'tipoResidencia', '') . "\n"
            . 'Estado: ' . (string) obtener_valor($datos, 'estadoHabitacion', '') . "\n"
            . 'Municipio: ' . (string) obtener_valor($datos, 'municipioHabitacion', '') . "\n"
            . 'Parroquia: ' . (string) obtener_valor($datos, 'parroquiaHabitacion', '') . "\n"
            . 'Ciudad/Pueblo: ' . (string) obtener_valor($datos, 'ciudadHabitacion', '') . "\n"
            . 'Avenida/Calle/Vereda: ' . (string) obtener_valor($datos, 'avenidaCalle', '') . "\n"
            . 'Urbanización/Barrio/Sector: ' . (string) obtener_valor($datos, 'urbanizacionBarrio', '') . "\n\n"
            . "--- Datos laborales ---\n"
            . 'Tipo de institución: ' . (string) obtener_valor($datos, 'tipoInstitucion', '') . "\n"
            . 'Institución: ' . (string) obtener_valor($datos, 'nombreInstitucion', '') . "\n"
            . 'Antigüedad: ' . (string) obtener_valor($datos, 'antiguedad', '') . "\n\n"
            . "--- Datos académicos ---\n"
            . 'Área conocimiento: ' . (string) obtener_valor($datos, 'areaConocimiento', '') . "\n"
            . 'Nivel académico: ' . (string) obtener_valor($datos, 'nivelAcademico', '') . "\n"
            . 'Universidad: ' . (string) obtener_valor($datos, 'universidad', '') . "\n"
            . 'Título: ' . (string) obtener_valor($datos, 'tituloAcademico', '') . "\n"
            . 'Año graduación: ' . (string) obtener_valor($datos, 'anoGraduacion', '') . "\n"
            . 'Promedio: ' . (string) obtener_valor($datos, 'promedio', '') . "\n\n"
            . "--- Otros datos ---\n"
            . 'Beca: ' . (string) obtener_valor($datos, 'tipoBeca', '') . "\n"
            . 'Fecha ingreso UNEFA: ' . (string) obtener_valor($datos, 'fechaIngresoUnefa', '') . "\n"
            . 'Observaciones: ' . ($obs !== '' ? $obs : '—');
    }
}

// if (!function_exists('procesar_perfil_post')) {
//     function procesar_perfil_post($datos, &$lista_errores, &$estudiante)
//     {
//         $lista_errores = validar_datos_perfil($datos);
//         if (!empty($lista_errores)) {
//             return false;
//         }

//         $estudiante['nombre_completo'] = trim(
//             obtener_valor($datos, 'primerNombre', '') . ' '
//             . obtener_valor($datos, 'segundoNombre', '') . ' '
//             . obtener_valor($datos, 'primerApellido', '') . ' '
//             . obtener_valor($datos, 'segundoApellido', '')
//         );

//         $estudiante['ci'] = obtener_valor($datos, 'tipoDocumento', 'V') . '-' . preg_replace('/\D/', '', (string) obtener_valor($datos, 'cedula', ''));
//         $estudiante['promedio_general'] = (string) obtener_valor($datos, 'promedio', $estudiante['promedio_general']);

//         if (isset($GLOBALS['pdo']) && function_exists('query_actualizar_perfil_usuario') && isset($_SESSION['user_id'])) {
//             try {
//                 query_actualizar_perfil_usuario($GLOBALS['pdo'], (int) $_SESSION['user_id'], array(
//                     'nombres'   => trim(obtener_valor($datos, 'primerNombre', '') . ' ' . obtener_valor($datos, 'segundoNombre', '')),
//                     'apellidos' => trim(obtener_valor($datos, 'primerApellido', '') . ' ' . obtener_valor($datos, 'segundoApellido', '')),
//                     'telefono'  => preg_replace('/\D/', '', (string) obtener_valor($datos, 'celular', '')),
//                     'direccion' => armar_direccion_perfil($datos),
//                 ));
//             } catch (Exception $e) {
//                 $lista_errores['db'] = 'Hubo un problema al guardar tu perfil.';
//                 error_log('Error al guardar perfil: ' . $e->getMessage());
//                 return false;
//             }
//         }

//         return true;
//     }
// }

if (!function_exists('procesar_perfil_post')) {
    function procesar_perfil_post($datos, &$lista_errores, &$estudiante)
    {
        $lista_errores = validar_datos_perfil($datos);
        if (!empty($lista_errores)) {
            return false;
        }

        $usuario_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
        if ($usuario_id === 0 || !isset($GLOBALS['pdo'])) {
            $lista_errores['sistema'] = 'Sesión inválida o error de conexión.';
            return false;
        }

        $pdo = $GLOBALS['pdo'];

        // 1. Preparar el directorio físico para los documentos adjuntos
        $dir_uploads = __DIR__ . '/../uploads/documentos/';
        if (!file_exists($dir_uploads)) {
            mkdir($dir_uploads, 0777, true);
        }

        // Generar nombres únicos para evitar colisiones
        $ext_ci = pathinfo($_FILES['archivo_ci']['name'], PATHINFO_EXTENSION);
        $ext_titulo = pathinfo($_FILES['archivo_titulo']['name'], PATHINFO_EXTENSION);
        
        $nom_ci = 'ci_' . $usuario_id . '_' . time() . '.' . $ext_ci;
        $nom_titulo = 'titulo_' . $usuario_id . '_' . time() . '.' . $ext_titulo;

        $ruta_fisica_ci = $dir_uploads . $nom_ci;
        $ruta_fisica_titulo = $dir_uploads . $nom_titulo;

        // 2. Mover físicamente los archivos subidos al servidor
        if (!move_uploaded_file($_FILES['archivo_ci']['tmp_name'], $ruta_fisica_ci) || 
            !move_uploaded_file($_FILES['archivo_titulo']['tmp_name'], $ruta_fisica_titulo)) {
            $lista_errores['archivos'] = 'Error al guardar los documentos adjuntos en el servidor.';
            return false;
        }

        try {
            // 3. ¡INICIAMOS LA TRANSACCIÓN DBA RECOMENDADA!
            $pdo->beginTransaction();

            // Mapeo Paso 1: Datos de Identidad Base
            query_actualizar_perfil_usuario_base($pdo, $usuario_id, array(
                'nombres'   => trim(obtener_valor($datos, 'primerNombre', '') . ' ' . obtener_valor($datos, 'segundoNombre', '')),
                'apellidos' => trim(obtener_valor($datos, 'primerApellido', '') . ' ' . obtener_valor($datos, 'segundoApellido', '')),
                'telefono'  => preg_replace('/\D/', '', (string) obtener_valor($datos, 'celular', ''))
            ));

            // Mapeo Paso 2: Dirección de Habitación Normalizada
            query_guardar_direccion($pdo, $usuario_id, array(
                'parroquia_id'               => query_obtener_o_crear_parroquia_id($pdo, $datos),
                'ciudad_pueblo'              => obtener_valor($datos, 'ciudadHabitacion', ''),
                'avenida_calle_vereda'       => obtener_valor($datos, 'avenidaCalle', ''),
                'urbanizacion_barrio_sector' => obtener_valor($datos, 'urbanizacionBarrio', ''),
                'tipo_residencia'            => obtener_valor($datos, 'tipoResidencia', ''),
                'residencia_detalles'        => armar_residencia_detalles_perfil($datos)
            ));

            // Mapeo Paso 3: Redes Sociales y Contactos
            query_guardar_contacto($pdo, $usuario_id, array(
                'twitter'           => obtener_valor($datos, 'twitter', null),
                'facebook'          => obtener_valor($datos, 'facebook', null),
                'instagram'         => obtener_valor($datos, 'instagram', null),
                'linkedin'          => obtener_valor($datos, 'linkedin', null),
                'telefono_fijo'     => preg_replace('/\D/', '', (string) obtener_valor($datos, 'telefono', '')),
                'celular'           => preg_replace('/\D/', '', (string) obtener_valor($datos, 'celular', '')),
                'condicion_ingreso' => obtener_valor($datos, 'condicion', ''),
                'condicion_usuario' => obtener_valor($datos, 'condicionUsuario', '')
            ));

            // Mapeo Paso 4: Datos Académicos y Laborales Profesionales
            query_guardar_academicos_laborales($pdo, $usuario_id, array(
                'area_conocimiento'  => obtener_valor($datos, 'areaConocimiento', ''),
                'nivel_academico'    => obtener_valor($datos, 'nivelAcademico', ''),
                'universidad'        => obtener_valor($datos, 'universidad', ''),
                'titulo_obtenido'    => obtener_valor($datos, 'tituloAcademico', ''),
                'ano_graduacion'     => ano_graduacion_desde_datos($datos),
                'promedio'           => obtener_valor($datos, 'promedio', 0.0),
                'tipo_institucion'   => obtener_valor($datos, 'tipoInstitucion', ''),
                'nombre_institucion' => obtener_valor($datos, 'nombreInstitucion', ''),
                'antiguedad'         => obtener_valor($datos, 'antiguedad', ''),
                'telefono_trabajo'   => preg_replace('/\D/', '', (string) obtener_valor($datos, 'telefonoTrabajo', '')),
                'cargo'              => obtener_valor($datos, 'cargo', ''),
                'trabaja_unefa'      => obtener_valor($datos, 'trabajaUnefa', '')
            ));

            // Mapeo Paso 5: Interés Investigativo y Rutas de los Archivos Guardados
            query_guardar_finalizacion_paso5($pdo, $usuario_id, array(
                'tema_interes'        => obtener_valor($datos, 'temaInvestigacion', ''),
                'cuenta_con_beca'     => cuenta_con_beca_desde_datos($datos),
                'fecha_ingreso_unefa' => fecha_ingreso_unefa_desde_datos($datos),
                'ruta_ci'             => 'uploads/documentos/' . $nom_ci,
                'ruta_titulo'         => 'uploads/documentos/' . $nom_titulo
            ));

            // Si todo se ejecutó sin errores, consolidamos la BD en un solo golpe atómico
            $pdo->commit();

            // Sincronizamos los datos necesarios para la vista de retorno
            $estudiante['nombre_completo'] = trim(obtener_valor($datos, 'primerNombre', '') . ' ' . obtener_valor($datos, 'primerApellido', ''));
            $estudiante['ci'] = obtener_valor($datos, 'tipoDocumento', 'V') . '-' . preg_replace('/\D/', '', (string) obtener_valor($datos, 'cedula', ''));
            
            return true;

        } catch (Exception $e) {
            // Si algo falla, limpiamos la base de datos para que no queden registros a medias
            $pdo->rollBack();

            // Borramos los archivos físicos para no dejar basura en el servidor local
            if (file_exists($ruta_fisica_ci)) unlink($ruta_fisica_ci);
            if (file_exists($ruta_fisica_titulo)) unlink($ruta_fisica_titulo);

            $lista_errores['db'] = 'Error crítico al procesar la preinscripción en el servidor.';
            error_log('Error en transacción de perfil: ' . $e->getMessage());
            return false;
        }
    }
}