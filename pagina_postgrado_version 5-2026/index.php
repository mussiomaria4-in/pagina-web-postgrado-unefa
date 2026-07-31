<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$lista_errores = array();
$datos_form = array();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/paths_helper.php';
require_once __DIR__ . '/includes/procesar.php';
require_once __DIR__ . '/includes/procesar_perfil.php';
require_once __DIR__ . '/queries/queries_usuarios.php';

if (empty($_SESSION['user_id'])) {
    header('Location: ' . app_url('Inicio.php'));
    exit;
}

$usuario_sesion = query_usuario_por_id($pdo, (int) $_SESSION['user_id']);
if (!$usuario_sesion) {
    $_SESSION = array();
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    header('Location: ' . app_url('Inicio.php'));
    exit;
}

$nombre_completo = trim(
    (string) ($usuario_sesion['nombres'] ?? '') . ' ' . (string) ($usuario_sesion['apellidos'] ?? '')
);
if ($nombre_completo === '') {
    $nombre_completo = isset($_SESSION['nombre_completo']) ? (string) $_SESSION['nombre_completo'] : 'Usuario';
}

$estudiante = array(
    'nombre_completo' => $nombre_completo,
    'ci' => ($usuario_sesion['tipo_cedula'] ?? 'V') . '-' . ($usuario_sesion['cedula'] ?? ''),
    'programa' => 'Maestría en Gerencia de Empresas',
    'nucleo' => 'Núcleo Caracas',
    'cohorte' => 'Cohorte 2024-I',
    'promedio_general' => '17.2',
    'promedio_termino_1' => '17.0',
    'promedio_termino_2' => '17.3',
    'fecha_sistema' => date('d/m/Y H:i'),
);

require_once __DIR__ . '/queries/queries_baremo.php';
$preguntas_por_categoria = array();
try {
    if (isset($pdo) && $pdo instanceof PDO) {
        $preguntas_por_categoria = query_baremo_preguntas_por_categoria($pdo);
    }
} catch (Throwable $e) {
    error_log('Baremo (query_baremo_preguntas_por_categoria): ' . $e->getMessage());
    $preguntas_por_categoria = array();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos_form = procesar_datos_desde_post();
    $guardado_ok = procesar_perfil_post($datos_form, $lista_errores, $estudiante);
    if ($guardado_ok) {
        header('Location: ' . app_url('index.php') . '?perfil=guardado');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sistema de Registro de Postgrado — UNEFA</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= htmlspecialchars(app_url('assets/css/style.css'), ENT_QUOTES, 'UTF-8') ?>" />
</head>
<body>

<?php include_once __DIR__ . '/includes/header.php'; ?>

<?php include_once __DIR__ . '/includes/sidebar.php'; ?>



<!-- MAIN -->
<main>
<div id="tab-inicio" class="tab-content active">

  <!-- STATS -->
  <div class="section-title">Resumen Académico</div>
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-icon blue">📚</div>
      <div class="stat-info">
        <strong>6</strong>
        <span>Materias Inscritas</span>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon gold">🎓</div>
      <div class="stat-info">
        <strong>18 UC</strong>
        <span>Unidades Crédito</span>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green">✔</div>
      <div class="stat-info">
        <strong>Al día</strong>
        <span>Estado de Solvencia</span>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon red">📋</div>
      <div class="stat-info">
        <strong>2</strong>
        <span>Solicitudes Pendientes</span>
      </div>
    </div>
  </div>

  <!-- MODULES -->
  <div class="section-title">Módulos del Sistema</div>
  <div class="modules-grid">

    <div class="module-card" style="--accent:#0d1b4b; --icon-bg:rgba(13,27,75,.08)">
      <div class="module-icon">📝</div>
      <h3>Inscripción de Materias</h3>
      <p>Registra y gestiona tu carga académica del período vigente.</p>
      <span class="module-arrow">→</span>
    </div>

    <div class="module-card" style="--accent:#b91c1c; --icon-bg:rgba(185,28,28,.08)">
      <div class="module-icon">💳</div>
      <h3>Pagos y Aranceles</h3>
      <p>Consulta y registra tus pagos, solvencias y recibos.</p>
      <span class="module-arrow">→</span>
    </div>

    <div class="module-card" style="--accent:#d4a017; --icon-bg:rgba(212,160,23,.1)">
      <div class="module-icon">📄</div>
      <h3>Expediente Académico</h3>
      <p>Revisa tus notas, historial y estado de avance del programa.</p>
      <span class="module-arrow">→</span>
    </div>

    <div class="module-card" style="--accent:#0d1b4b; --icon-bg:rgba(13,27,75,.08)">
      <div class="module-icon">🗂</div>
      <h3>Documentos</h3>
      <p>Descarga constancias, certificados y recaudos requeridos.</p>
      <span class="module-arrow">→</span>
    </div>

    <div class="module-card" style="--accent:#16a34a; --icon-bg:rgba(34,197,94,.08)">
      <div class="module-icon">📅</div>
      <h3>Horarios</h3>
      <p>Consulta el horario de clases y calendario académico.</p>
      <span class="module-arrow">→</span>
    </div>



  </div>

  <!-- NOTICES + SCHEDULE -->
  <div class="two-col">
    <div>
      <div class="section-title">Avisos y Notificaciones</div>
      <div class="panel">
        <div class="panel-header">
          <h4>Recientes</h4>
          <a href="#">Ver todos →</a>
        </div>
        <div class="panel-body">
          <div class="notice-item">
            <span class="notice-dot red"></span>
            <div class="notice-text">
              <strong>Período de inscripción abierto</strong>
              <span>Vence el 30 de Abril de 2026</span>
            </div>
          </div>
          <div class="notice-item">
            <span class="notice-dot gold"></span>
            <div class="notice-text">
              <strong>Pago de arancel pendiente</strong>
              <span>Segundo trimestre 2026</span>
            </div>
          </div>
          <div class="notice-item">
            <span class="notice-dot blue"></span>
            <div class="notice-text">
              <strong>Actualización de datos personales</strong>
              <span>Requerida por Secretaría</span>
            </div>
          </div>
          <div class="notice-item">
            <span class="notice-dot blue"></span>
            <div class="notice-text">
              <strong>Calendario académico publicado</strong>
              <span>Trimestre Abril–Julio 2026</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div>
      <div class="section-title">Próximas Clases</div>
      <div class="panel">
        <div class="panel-header">
          <h4>Esta semana</h4>
          <a href="#">Ver horario →</a>
        </div>
        <div class="panel-body">
          <div class="schedule-item">
            <div class="schedule-day">Lun<br/>21</div>
            <div class="schedule-info">
              <strong>Metodología de la Investigación</strong>
              <span>8:00 am – 12:00 m &nbsp;·&nbsp; Aula 304</span>
            </div>
          </div>
          <div class="schedule-item">
            <div class="schedule-day">Mar<br/>22</div>
            <div class="schedule-info">
              <strong>Gerencia Estratégica</strong>
              <span>2:00 pm – 6:00 pm &nbsp;·&nbsp; Aula 201</span>
            </div>
          </div>
          <div class="schedule-item">
            <div class="schedule-day">Jue<br/>24</div>
            <div class="schedule-info">
              <strong>Estadística Aplicada</strong>
              <span>8:00 am – 12:00 m &nbsp;·&nbsp; Lab. Cómputo</span>
            </div>
          </div>
          <div class="schedule-item">
            <div class="schedule-day">Vie<br/>25</div>
            <div class="schedule-info">
              <strong>Derecho Administrativo</strong>
              <span>2:00 pm – 6:00 pm &nbsp;·&nbsp; Aula 104</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div><!-- /tab-inicio -->

<!-- ══════════════════════════════════════════════════
     TAB: EXPEDIENTE ACADÉMICO
══════════════════════════════════════════════════ -->
<div id="tab-expediente" class="tab-content" style="display:none">

  <!-- Cabecera del expediente -->
  <div class="exp-header-card">
    <div class="exp-header-left">
      <div class="exp-avatar">ACG</div>
      <div>
        <h2><?= htmlspecialchars($estudiante['nombre_completo']) ?></h2>
        <p>C.I.: <?= htmlspecialchars($estudiante['ci']) ?> &nbsp;·&nbsp; <?= htmlspecialchars($estudiante['programa']) ?></p>
        <p><?= htmlspecialchars($estudiante['nucleo']) ?> &nbsp;·&nbsp; <?= htmlspecialchars($estudiante['cohorte']) ?></p>
      </div>
    </div>
    <div class="exp-header-actions">
      <button class="exp-btn exp-btn-outline" onclick="alert('Generando constancia de notas...')">Ver Constancia</button>
      <button class="exp-btn exp-btn-solid" onclick="alert('Descargando expediente en PDF...')">Descargar PDF</button>
    </div>
  </div>

  <!-- Stats del expediente -->
  <div class="exp-stats">
    <div class="exp-stat">
      <div class="exp-stat-val"><?= htmlspecialchars($estudiante['promedio_general']) ?></div>
      <div class="exp-stat-label">Promedio General</div>
      <div class="exp-stat-bar"><div class="exp-stat-fill" style="width:86%; background:var(--navy)"></div></div>
    </div>
    <div class="exp-stat">
      <div class="exp-stat-val">18 / 36</div>
      <div class="exp-stat-label">UC Cursadas / Requeridas</div>
      <div class="exp-stat-bar"><div class="exp-stat-fill" style="width:50%; background:var(--gold)"></div></div>
    </div>
    <div class="exp-stat">
      <div class="exp-stat-val">6 / 6</div>
      <div class="exp-stat-label">Materias Aprobadas</div>
      <div class="exp-stat-bar"><div class="exp-stat-fill" style="width:100%; background:#16a34a"></div></div>
    </div>
    <div class="exp-stat">
      <div class="exp-stat-val">50%</div>
      <div class="exp-stat-label">Avance del Programa</div>
      <div class="exp-stat-bar">
        <div class="exp-stat-fill" style="width:50%; background:var(--red)"></div>
      </div>
    </div>
  </div>

  <!-- Avance del programa visual -->
  <div class="exp-section-title">Estado de Avance del Programa</div>
  <div class="exp-avance-card">
    <div class="exp-avance-row">
      <span>Término I — 2024</span>
      <div class="exp-avance-bar-wrap"><div class="exp-avance-bar" style="width:100%"></div></div>
      <span class="exp-avance-pct">Completado</span>
    </div>
    <div class="exp-avance-row">
      <span>Término II — 2024</span>
      <div class="exp-avance-bar-wrap"><div class="exp-avance-bar" style="width:100%"></div></div>
      <span class="exp-avance-pct">Completado</span>
    </div>
    <div class="exp-avance-row">
      <span>Término III — 2025</span>
      <div class="exp-avance-bar-wrap"><div class="exp-avance-bar" style="width:60%; background:var(--gold)"></div></div>
      <span class="exp-avance-pct" style="color:var(--gold)">En curso</span>
    </div>
    <div class="exp-avance-row">
      <span>Término IV — 2025</span>
      <div class="exp-avance-bar-wrap"><div class="exp-avance-bar" style="width:0%; background:var(--border)"></div></div>
      <span class="exp-avance-pct" style="color:var(--muted)">Pendiente</span>
    </div>
    <div class="exp-avance-row">
      <span>Trabajo de Grado</span>
      <div class="exp-avance-bar-wrap"><div class="exp-avance-bar" style="width:0%; background:var(--border)"></div></div>
      <span class="exp-avance-pct" style="color:var(--muted)">Pendiente</span>
    </div>
  </div>

  <!-- Tabla de notas por término -->
  <div class="exp-section-title">Historial de Notas por Término</div>

  <!-- Selector de término -->
  <div class="exp-term-tabs">
    <button class="exp-term-tab active" onclick="showTerm(this,'term1')">Término I</button>
    <button class="exp-term-tab" onclick="showTerm(this,'term2')">Término II</button>
    <button class="exp-term-tab" onclick="showTerm(this,'term3')">Término III</button>
  </div>

  <!-- Término I -->
  <div id="term1" class="exp-term-panel">
    <table class="notas-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Asignatura</th>
          <th>UC</th>
          <th>Profesor</th>
          <th>Nota</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>PG-101</td><td>Metodología de la Investigación</td><td>3</td><td>Dr. Ramírez</td>
          <td><span class="nota-badge aprobado">18</span></td>
          <td><span class="estado-badge aprobado">Aprobado</span></td>
        </tr>
        <tr>
          <td>PG-102</td><td>Gerencia Estratégica I</td><td>3</td><td>Dra. Morales</td>
          <td><span class="nota-badge aprobado">16</span></td>
          <td><span class="estado-badge aprobado">Aprobado</span></td>
        </tr>
        <tr>
          <td>PG-103</td><td>Estadística Aplicada</td><td>3</td><td>Dr. Hernández</td>
          <td><span class="nota-badge aprobado">17</span></td>
          <td><span class="estado-badge aprobado">Aprobado</span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr><td colspan="2"><strong>Promedio del Término</strong></td><td colspan="2"></td><td colspan="2"><strong><?= htmlspecialchars($estudiante['promedio_termino_1']) ?></strong></td></tr>
      </tfoot>
    </table>
  </div>

  <!-- Término II -->
  <div id="term2" class="exp-term-panel" style="display:none">
    <table class="notas-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Asignatura</th>
          <th>UC</th>
          <th>Profesor</th>
          <th>Nota</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>PG-201</td><td>Gerencia Estratégica II</td><td>3</td><td>Dra. Morales</td>
          <td><span class="nota-badge aprobado">17</span></td>
          <td><span class="estado-badge aprobado">Aprobado</span></td>
        </tr>
        <tr>
          <td>PG-202</td><td>Derecho Administrativo</td><td>3</td><td>Dr. Gutiérrez</td>
          <td><span class="nota-badge aprobado">18</span></td>
          <td><span class="estado-badge aprobado">Aprobado</span></td>
        </tr>
        <tr>
          <td>PG-203</td><td>Finanzas Corporativas</td><td>3</td><td>Dra. Castillo</td>
          <td><span class="nota-badge aprobado">17</span></td>
          <td><span class="estado-badge aprobado">Aprobado</span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr><td colspan="2"><strong>Promedio del Término</strong></td><td colspan="2"></td><td colspan="2"><strong><?= htmlspecialchars($estudiante['promedio_termino_2']) ?></strong></td></tr>
      </tfoot>
    </table>
  </div>

  <!-- Término III (en curso) -->
  <div id="term3" class="exp-term-panel" style="display:none">
    <div class="exp-en-curso-banner">Término en curso — Notas definitivas disponibles al finalizar el período</div>
    <table class="notas-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Asignatura</th>
          <th>UC</th>
          <th>Profesor</th>
          <th>Nota</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>PG-301</td><td>Liderazgo y Gestión del Talento</td><td>3</td><td>Dr. Pérez</td>
          <td><span class="nota-badge pendiente">—</span></td>
          <td><span class="estado-badge en-curso">En curso</span></td>
        </tr>
        <tr>
          <td>PG-302</td><td>Innovación y Emprendimiento</td><td>3</td><td>Dra. López</td>
          <td><span class="nota-badge pendiente">—</span></td>
          <td><span class="estado-badge en-curso">En curso</span></td>
        </tr>
        <tr>
          <td>PG-303</td><td>Sistemas de Información Gerencial</td><td>3</td><td>Dr. Flores</td>
          <td><span class="nota-badge pendiente">—</span></td>
          <td><span class="estado-badge en-curso">En curso</span></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Trabajo de Grado -->
  <div class="exp-section-title" style="margin-top:28px">Trabajo de Grado / Tesis</div>
  <div class="tesis-card">
    <div class="tesis-estado pendiente-tesis">
      <span>Estado: <strong>No iniciado</strong></span>
    </div>
    <div class="tesis-info-grid">
      <div><label>Título tentativo</label><span>Por definir</span></div>
      <div><label>Tutor asignado</label><span>Por asignar</span></div>
      <div><label>Línea de investigación</label><span>Por definir</span></div>
      <div><label>Fecha estimada de defensa</label><span>Por definir</span></div>
    </div>
    <div class="tesis-pasos">
      <div class="tesis-paso pendiente-paso"><span class="tpaso-num">1</span> Propuesta de tema</div>
      <div class="tesis-paso pendiente-paso"><span class="tpaso-num">2</span> Aprobación del tutor</div>
      <div class="tesis-paso pendiente-paso"><span class="tpaso-num">3</span> Desarrollo del proyecto</div>
      <div class="tesis-paso pendiente-paso"><span class="tpaso-num">4</span> Entrega del trabajo</div>
      <div class="tesis-paso pendiente-paso"><span class="tpaso-num">5</span> Defensa pública</div>
    </div>
  </div>

  <!-- Observaciones -->
  <div class="exp-section-title" style="margin-top:28px">Observaciones Académicas</div>
  <div class="obs-card">
    <div class="obs-empty">Sin observaciones ni sanciones registradas en el expediente.</div>
  </div>

  <!-- Nota informativa -->
  <div class="exp-info-nota">
    Para solicitar notas certificadas o copia oficial del expediente, diríjase a la Secretaría de la Coordinación de Postgrado de su núcleo de forma presencial.
  </div>

</div><!-- /tab-expediente -->

</div><!-- /tab-expediente -->

<?php include_once __DIR__ . '/includes/tab-inscripciones.php'; ?>

<?php include_once __DIR__ . '/includes/tab-pagos.php'; ?>

<!-- ══════════════════════════════════════════════════
     TAB: DOCUMENTOS
══════════════════════════════════════════════════ -->
<div id="tab-documentos" class="tab-content" style="display:none">

  <!-- Cabecera -->
  <div class="docs-header-banner">
    <div class="docs-header-left">
      <div class="docs-header-icon">🗂</div>
      <div>
        <h2>Centro de Documentos</h2>
        <p>Descarga tus documentos académicos o sube los recaudos solicitados por la coordinación.</p>
      </div>
    </div>
  </div>

  <div class="docs-layout">

    <!-- Izquierda: documentos descargables -->
    <div class="docs-main">

      <!-- Descarga automática -->
      <div class="exp-section-title">Documentos disponibles para descarga</div>
      <div class="docs-grid-cards">

        <div class="doc-card">
          <div class="doc-card-icon" style="background:rgba(13,27,75,.08);color:var(--navy)">📄</div>
          <div class="doc-card-info">
            <strong>Constancia de Estudios</strong>
            <span>Certifica que eres estudiante activo del programa de postgrado.</span>
          </div>
          <button class="doc-dl-btn" onclick="descargarDoc('Constancia de Estudios')">↓ PDF</button>
        </div>

        <div class="doc-card">
          <div class="doc-card-icon" style="background:rgba(13,27,75,.08);color:var(--navy)">📊</div>
          <div class="doc-card-info">
            <strong>Constancia de Notas</strong>
            <span>Historial de calificaciones de todos los términos cursados.</span>
          </div>
          <button class="doc-dl-btn" onclick="descargarDoc('Constancia de Notas')">↓ PDF</button>
        </div>

        <div class="doc-card">
          <div class="doc-card-icon" style="background:rgba(22,163,74,.08);color:#16a34a">✅</div>
          <div class="doc-card-info">
            <strong>Constancia de Inscripción</strong>
            <span>Comprobante oficial de inscripción del período vigente.</span>
          </div>
          <button class="doc-dl-btn" onclick="descargarDoc('Constancia de Inscripción')">↓ PDF</button>
        </div>

        <div class="doc-card">
          <div class="doc-card-icon" style="background:rgba(22,163,74,.08);color:#16a34a">🎓</div>
          <div class="doc-card-info">
            <strong>Solvencia Académica</strong>
            <span>Certificado de no tener deudas académicas pendientes.</span>
          </div>
          <button class="doc-dl-btn" onclick="descargarDoc('Solvencia Académica')">↓ PDF</button>
        </div>

        <div class="doc-card">
          <div class="doc-card-icon" style="background:rgba(22,163,74,.08);color:#16a34a">💰</div>
          <div class="doc-card-info">
            <strong>Solvencia de Pago</strong>
            <span>Constancia de haber cancelado todos los aranceles del período.</span>
          </div>
          <button class="doc-dl-btn" onclick="descargarDoc('Solvencia de Pago')">↓ PDF</button>
        </div>

        <div class="doc-card">
          <div class="doc-card-icon" style="background:rgba(212,160,23,.08);color:var(--gold)">🏆</div>
          <div class="doc-card-info">
            <strong>Certificado de Culminación</strong>
            <span>Disponible al completar el 100% del programa de postgrado.</span>
          </div>
          <button class="doc-dl-btn disabled" onclick="alert('Disponible al culminar el programa.')">No disponible</button>
        </div>

        <div class="doc-card">
          <div class="doc-card-icon" style="background:rgba(13,27,75,.08);color:var(--navy)">📋</div>
          <div class="doc-card-info">
            <strong>Carta de Buena Conducta</strong>
            <span>Certificado de conducta académica y disciplinaria.</span>
          </div>
          <button class="doc-dl-btn" onclick="descargarDoc('Carta de Buena Conducta')">↓ PDF</button>
        </div>

        <div class="doc-card">
          <div class="doc-card-icon" style="background:rgba(13,27,75,.08);color:var(--navy)">📚</div>
          <div class="doc-card-info">
            <strong>Pensum del Programa</strong>
            <span>Plan de estudios oficial del programa de postgrado inscrito.</span>
          </div>
          <button class="doc-dl-btn" onclick="descargarDoc('Pensum del Programa')">↓ PDF</button>
        </div>

        <div class="doc-card">
          <div class="doc-card-icon" style="background:rgba(13,27,75,.08);color:var(--navy)">📝</div>
          <div class="doc-card-info">
            <strong>Comprobante de Inscripción</strong>
            <span>Recibo del proceso de inscripción del período actual.</span>
          </div>
          <button class="doc-dl-btn" onclick="descargarDoc('Comprobante de Inscripción')">↓ PDF</button>
        </div>

        <div class="doc-card">
          <div class="doc-card-icon" style="background:rgba(185,28,28,.08);color:var(--red)">🧾</div>
          <div class="doc-card-info">
            <strong>Recibo de Pago</strong>
            <span>Último recibo de pago de aranceles registrado y verificado.</span>
          </div>
          <button class="doc-dl-btn" onclick="descargarDoc('Recibo de Pago')">↓ PDF</button>
        </div>

      </div><!-- /docs-grid-cards -->

      <!-- Historial de documentos descargados -->
      <div class="exp-section-title" style="margin-top:28px">Historial de descargas recientes</div>
      <div class="panel">
        <div class="panel-body">
          <div class="historial-doc-row">
            <span class="hd-icono">📄</span>
            <div class="hd-info">
              <strong>Constancia de Estudios</strong>
              <span>Descargado el 18/04/2026 · 10:32 am</span>
            </div>
            <button class="hp-recibo" onclick="descargarDoc('Constancia de Estudios')">↓ Volver a descargar</button>
          </div>
          <div class="historial-doc-row">
            <span class="hd-icono">💰</span>
            <div class="hd-info">
              <strong>Solvencia de Pago</strong>
              <span>Descargado el 17/04/2026 · 2:15 pm</span>
            </div>
            <button class="hp-recibo" onclick="descargarDoc('Solvencia de Pago')">↓ Volver a descargar</button>
          </div>
          <div class="historial-doc-row">
            <span class="hd-icono">📊</span>
            <div class="hd-info">
              <strong>Constancia de Notas</strong>
              <span>Descargado el 10/04/2026 · 9:05 am</span>
            </div>
            <button class="hp-recibo" onclick="descargarDoc('Constancia de Notas')">↓ Volver a descargar</button>
          </div>
        </div>
      </div>

    </div><!-- /docs-main -->

    <!-- Derecha: subir documentos -->
    <div class="docs-right">

      <div class="registrar-pago-card">
        <div class="rp-header">Subir Documentos al Sistema</div>
        <div class="rp-body">

          <div class="docs-instrucciones">
            <strong>Instrucciones</strong>
            <ul>
              <li>Solo se aceptan archivos JPG, PNG o PDF.</li>
              <li>Peso máximo: 5 MB por archivo.</li>
              <li>Los documentos serán revisados por la Coordinación.</li>
            </ul>
          </div>

          <div class="form-group" style="margin-top:14px">
            <label>Tipo de documento <span class="req">*</span></label>
            <select>
              <option value="">Seleccione</option>
              <option>Cédula de Identidad</option>
              <option>Título universitario</option>
              <option>Notas certificadas</option>
              <option>Constancia de trabajo</option>
              <option>Foto tipo carnet</option>
              <option>Partida de nacimiento</option>
              <option>Comprobante de pago</option>
              <option>Otro documento</option>
            </select>
          </div>

          <div class="form-group">
            <label>Descripción / Observación</label>
            <textarea rows="2" placeholder="Opcional — describa el documento que sube..."></textarea>
          </div>

          <div class="form-group">
            <label>Seleccionar archivo <span class="req">*</span></label>
            <div class="file-upload-box">
              <input type="file" accept=".jpg,.png,.pdf" id="file-doc-upload" onchange="updateFileName(this,'lbl-doc-upload')" />
              <label for="file-doc-upload" class="file-upload-label">
                <span class="file-btn">Seleccionar archivo</span>
                <span class="file-name" id="lbl-doc-upload">Sin archivo seleccionado</span>
              </label>
              <small>JPG, PNG o PDF — máx. 5 MB</small>
            </div>
          </div>

          <button class="btn-inscribir" style="width:100%;margin-top:10px" onclick="alert('Documento subido exitosamente.\nLa Coordinación lo revisará en un plazo de 24-48 horas hábiles.')">Subir Documento</button>

          <!-- Documentos subidos -->
          <div class="docs-subidos-titulo">Documentos subidos</div>
          <div class="docs-subidos-lista">
            <div class="doc-subido-item">
              <span class="doc-subido-tipo">Cédula de Identidad</span>
              <span class="doc-subido-estado aprobado">Verificado</span>
            </div>
            <div class="doc-subido-item">
              <span class="doc-subido-tipo">Título universitario</span>
              <span class="doc-subido-estado aprobado">Verificado</span>
            </div>
            <div class="doc-subido-item">
              <span class="doc-subido-tipo">Notas certificadas</span>
              <span class="doc-subido-estado pendiente-doc">En revisión</span>
            </div>
          </div>

        </div>
      </div>

    </div><!-- /docs-right -->
  </div><!-- /docs-layout -->

</div><!-- /tab-documentos -->

<?php include_once __DIR__ . '/includes/tab-preinscripcion.php'; ?>

</main>

<!-- FOOTER -->
<footer>
  <strong>UNEFA</strong> | Excelencia Educativa Abierta al Pueblo<br/>
  Vicerrectorado de Investigación, Postgrado y Recreación
</footer>

<!-- TWEAKS -->
<div id="tweaks-panel">
  <h5>Tweaks</h5>

  <label>Nombre del usuario</label>
  <select id="tw-name" onchange="applyTweaks()">
    <option value="<?= htmlspecialchars($estudiante['nombre_completo']) ?>"><?= htmlspecialchars($estudiante['nombre_completo']) ?></option>
    <option value="Carlos Rodríguez López">Carlos Rodríguez López</option>
    <option value="María Fernández">María Fernández</option>
  </select>

  <label>Acento de módulos</label>
  <select id="tw-accent" onchange="applyTweaks()">
    <option value="multi">Multicolor (original)</option>
    <option value="navy">Todo azul marino</option>
    <option value="red">Todo rojo</option>
  </select>

  <label>Compacidad</label>
  <input type="range" id="tw-density" min="0.8" max="1.2" step="0.05" value="1" oninput="applyTweaks()" />
</div>

<script src="<?= htmlspecialchars(app_url('assets/js/scripts.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>
</html>
