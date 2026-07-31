<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/paths_helper.php';
require_once __DIR__ . '/procesar.php';
require_once __DIR__ . '/../queries/queries_usuarios.php';

$es_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

$lista_errores = array();
$url_inicio = app_url('Inicio.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('status' => 'error', 'message' => 'Método no permitido'));
    exit;
}

$datos = procesar_datos_desde_post();

$errores = array(
    'tipoDocumento' => array(
        'validar' => 'campo_no_vacio',
        'mensaje' => 'Selecciona una opción de tipo de documento',
    ),
    'cedula' => array(
        'validar' => 'cedula_es_valida',
        'mensaje' => 'Cédula incorrecta',
    ),
    'primerNombre' => array(
        'validar' => 'campo_no_vacio',
        'mensaje' => 'Indica tu primer nombre',
    ),
    'primerApellido' => array(
        'validar' => 'campo_no_vacio',
        'mensaje' => 'Indica tu primer apellido',
    ),
    'segundoApellido' => array(
        'validar' => 'campo_no_vacio',
        'mensaje' => 'Indica tu segundo apellido',
    ),
    'email' => array(
        'validar' => 'email_es_valido',
        'mensaje' => 'Error en el correo electrónico',
    ),
    'password' => array(
        'validar' => 'password_es_valido',
        'mensaje' => 'La contraseña debe tener al menos 8 caracteres y una mayúscula',
    ),
    'confirm_password' => array(
        'validar' => function ($valor, $todos_los_datos) {
            $valor = (string) $valor;
            $validacion_formato = password_es_valido($valor);
            $original = isset($todos_los_datos['password']) ? (string) $todos_los_datos['password'] : '';

            return $validacion_formato && hash_equals($original, $valor);
        },
        'mensaje' => 'Las claves no coinciden. Por favor ingresa la clave nuevamente',
    ),
);

foreach ($errores as $campo => $array_interno) {
    $recibir = isset($datos[$campo]) ? $datos[$campo] : '';

    $es_valido = is_string($array_interno['validar'])
        ? call_user_func($array_interno['validar'], $recibir)
        : call_user_func($array_interno['validar'], $recibir, $datos);

    if (!$es_valido) {
        $lista_errores[$campo] = $array_interno['mensaje'];
    }
}

if (empty($lista_errores)) {
    try {
        $cedula_limpia = preg_replace('/\D/', '', (string) $datos['cedula']);
        $tipo = (string) $datos['tipoDocumento'];
        $primerNom = trim((string) ($datos['primerNombre'] ?? ''));
        $segundoNom = trim((string) ($datos['segundoNombre'] ?? ''));
        $primerApe = trim((string) ($datos['primerApellido'] ?? ''));
        $segundoApe = trim((string) ($datos['segundoApellido'] ?? ''));

        $nombres = trim($primerNom . ' ' . $segundoNom);
        $apellidos = trim($primerApe . ' ' . $segundoApe);

        query_insertar_usuario_registro($pdo, array(
            'cedula_limpia'   => $cedula_limpia,
            'tipo'            => $tipo,
            'nombres'         => $nombres,
            'apellidos'       => $apellidos,
            'email'           => (string) $datos['email'],
            'password_hash'   => password_hash((string) $datos['password'], PASSWORD_BCRYPT),
        ));

        if ($es_ajax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array(
                'status'   => 'ok',
                'redirect' => $url_inicio,
            ));
            exit;
        }

        header('Location: ' . $url_inicio);
        exit;
    } catch (PDOException $e) {
        if ((string) $e->getCode() === '23505') {
            $mensaje_sql = $e->getMessage();
            if (stripos($mensaje_sql, 'telefono') !== false) {
                $lista_errores['telefono'] = 'Error: Este número de teléfono ya pertenece a un usuario registrado.';
            } elseif (stripos($mensaje_sql, 'email') !== false || stripos($mensaje_sql, 'correo') !== false) {
                $lista_errores['email'] = 'Error: Este correo electrónico ya está registrado en el sistema.';
            } elseif (stripos($mensaje_sql, 'cedula') !== false) {
                $lista_errores['cedula'] = 'Error: Esta cédula ya pertenece a un usuario registrado.';
            } else {
                $lista_errores['db'] = 'Uno de los datos ingresados ya se encuentra registrado. Verifica cédula y correo.';
            }
        } else {
            error_log('Error de registro: ' . $e->getMessage());
            $lista_errores['db'] = 'Hubo un problema de conexión con el servidor de la universidad.';
        }
    }
}

$mensaje = !empty($lista_errores) ? implode(' ', array_values($lista_errores)) : 'Error de validación';
header('Content-Type: application/json; charset=utf-8');
echo json_encode(array('status' => 'error', 'message' => $mensaje, 'errors' => $lista_errores));
