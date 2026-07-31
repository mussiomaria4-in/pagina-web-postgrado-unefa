<!-- ══════════════════════════════════════════════════
     TAB: INSCRIPCIONES
══════════════════════════════════════════════════ -->
<div id="tab-inscripciones" class="tab-content" style="display:none">

  <!-- Banner período -->
  <div class="insc-periodo-banner">
    <div class="insc-periodo-left">
      <span class="insc-periodo-badge">Período abierto</span>
      <h2>Inscripción — Término III · 2025</h2>
      <p>Período de inscripción: <strong>20 de Abril al 30 de Abril de 2026</strong></p>
    </div>
    <div class="insc-countdown">
      <div class="countdown-block"><span id="cd-dias">07</span><small>días</small></div>
      <div class="countdown-sep">:</div>
      <div class="countdown-block"><span id="cd-horas">14</span><small>horas</small></div>
      <div class="countdown-sep">:</div>
      <div class="countdown-block"><span id="cd-mins">32</span><small>min</small></div>
    </div>
  </div>

  <!-- Requisitos -->
  <div class="insc-requisitos">
    <div class="req-item ok"><span class="req-icon">✔</span><span>Solvencia de pago</span></div>
    <div class="req-item ok"><span class="req-icon">✔</span><span>Sin materias reprobadas pendientes</span></div>
    <div class="req-item ok"><span class="req-icon">✔</span><span>Documentos completos</span></div>
    <div class="req-item ok"><span class="req-icon">✔</span><span>Período de inscripción abierto</span></div>
    <div class="req-item pendiente-req"><span class="req-icon">⏳</span><span>Aprobación del coordinador</span></div>
  </div>

  <!-- Layout principal: lista + resumen -->
  <div class="insc-layout">

    <!-- Panel izquierdo: materias disponibles -->
    <div class="insc-panel-left">

      <!-- Filtros -->
      <div class="insc-filtros">
        <select class="insc-select" onchange="filtrarMaterias(this.value)">
          <option value="todas">Todas las secciones</option>
          <option value="A">Sección A</option>
          <option value="B">Sección B</option>
          <option value="C">Sección C</option>
        </select>
        <input class="insc-search" type="text" placeholder="Buscar materia..." oninput="buscarMateria(this.value)" />
      </div>

      <div class="insc-lista-header">
        <span>Materias disponibles</span>
        <span class="insc-uc-info">UC seleccionadas: <strong id="uc-sel">0</strong></span>
      </div>

      <!-- Lista de materias -->
      <div class="insc-materias" id="lista-materias">

        <div class="materia-card" data-codigo="PG-301" data-uc="3" data-seccion="A">
          <div class="materia-check-wrap">
            <input type="checkbox" id="m1" class="materia-chk" onchange="toggleMateria(this,'PG-301','Liderazgo y Gestión del Talento',3,'Lun/Mié 8:00–10:00am','Dr. Pérez','A')" />
            <label for="m1"></label>
          </div>
          <div class="materia-info">
            <div class="materia-top">
              <span class="materia-codigo">PG-301</span>
              <span class="materia-uc">3 UC</span>
              <span class="materia-sec">Sec. A</span>
            </div>
            <div class="materia-nombre">Liderazgo y Gestión del Talento</div>
            <div class="materia-detalles">
              <span>👨‍🏫 Dr. Pérez</span>
              <span>📅 Lun/Mié · 8:00–10:00 am</span>
              <span>🏛 Aula 304</span>
            </div>
          </div>
          <div class="materia-cupos"><strong>22</strong><small>cupos</small></div>
        </div>

        <div class="materia-card" data-codigo="PG-302" data-uc="3" data-seccion="A">
          <div class="materia-check-wrap">
            <input type="checkbox" id="m2" class="materia-chk" onchange="toggleMateria(this,'PG-302','Innovación y Emprendimiento',3,'Mar/Jue 2:00–4:00pm','Dra. López','A')" />
            <label for="m2"></label>
          </div>
          <div class="materia-info">
            <div class="materia-top">
              <span class="materia-codigo">PG-302</span>
              <span class="materia-uc">3 UC</span>
              <span class="materia-sec">Sec. A</span>
            </div>
            <div class="materia-nombre">Innovación y Emprendimiento</div>
            <div class="materia-detalles">
              <span>👨‍🏫 Dra. López</span>
              <span>📅 Mar/Jue · 2:00–4:00 pm</span>
              <span>🏛 Aula 201</span>
            </div>
          </div>
          <div class="materia-cupos"><strong>18</strong><small>cupos</small></div>
        </div>

        <div class="materia-card" data-codigo="PG-303" data-uc="3" data-seccion="B">
          <div class="materia-check-wrap">
            <input type="checkbox" id="m3" class="materia-chk" onchange="toggleMateria(this,'PG-303','Sistemas de Información Gerencial',3,'Vie 8:00am–12:00m','Dr. Flores','B')" />
            <label for="m3"></label>
          </div>
          <div class="materia-info">
            <div class="materia-top">
              <span class="materia-codigo">PG-303</span>
              <span class="materia-uc">3 UC</span>
              <span class="materia-sec">Sec. B</span>
            </div>
            <div class="materia-nombre">Sistemas de Información Gerencial</div>
            <div class="materia-detalles">
              <span>👨‍🏫 Dr. Flores</span>
              <span>📅 Vie · 8:00 am–12:00 m</span>
              <span>🏛 Lab. Cómputo</span>
            </div>
          </div>
          <div class="materia-cupos"><strong>30</strong><small>cupos</small></div>
        </div>

        <div class="materia-card" data-codigo="PG-304" data-uc="3" data-seccion="B">
          <div class="materia-check-wrap">
            <input type="checkbox" id="m4" class="materia-chk" onchange="toggleMateria(this,'PG-304','Derecho Laboral y Empresarial',3,'Sáb 8:00am–12:00m','Dra. Castillo','B')" />
            <label for="m4"></label>
          </div>
          <div class="materia-info">
            <div class="materia-top">
              <span class="materia-codigo">PG-304</span>
              <span class="materia-uc">3 UC</span>
              <span class="materia-sec">Sec. B</span>
            </div>
            <div class="materia-nombre">Derecho Laboral y Empresarial</div>
            <div class="materia-detalles">
              <span>👨‍🏫 Dra. Castillo</span>
              <span>📅 Sáb · 8:00 am–12:00 m</span>
              <span>🏛 Aula 104</span>
            </div>
          </div>
          <div class="materia-cupos"><strong>25</strong><small>cupos</small></div>
        </div>

        <div class="materia-card" data-codigo="PG-305" data-uc="3" data-seccion="C">
          <div class="materia-check-wrap">
            <input type="checkbox" id="m5" class="materia-chk" onchange="toggleMateria(this,'PG-305','Economía Internacional',3,'Lun/Mié 4:00–6:00pm','Dr. Rodríguez','C')" />
            <label for="m5"></label>
          </div>
          <div class="materia-info">
            <div class="materia-top">
              <span class="materia-codigo">PG-305</span>
              <span class="materia-uc">3 UC</span>
              <span class="materia-sec">Sec. C</span>
            </div>
            <div class="materia-nombre">Economía Internacional</div>
            <div class="materia-detalles">
              <span>👨‍🏫 Dr. Rodríguez</span>
              <span>📅 Lun/Mié · 4:00–6:00 pm</span>
              <span>🏛 Aula 210</span>
            </div>
          </div>
          <div class="materia-cupos"><strong>20</strong><small>cupos</small></div>
        </div>

        <div class="materia-card" data-codigo="PG-306" data-uc="3" data-seccion="C">
          <div class="materia-check-wrap">
            <input type="checkbox" id="m6" class="materia-chk" onchange="toggleMateria(this,'PG-306','Gestión de Proyectos',3,'Mar/Jue 6:00–8:00pm','Dra. Montilla','C')" />
            <label for="m6"></label>
          </div>
          <div class="materia-info">
            <div class="materia-top">
              <span class="materia-codigo">PG-306</span>
              <span class="materia-uc">3 UC</span>
              <span class="materia-sec">Sec. C</span>
            </div>
            <div class="materia-nombre">Gestión de Proyectos</div>
            <div class="materia-detalles">
              <span>👨‍🏫 Dra. Montilla</span>
              <span>📅 Mar/Jue · 6:00–8:00 pm</span>
              <span>🏛 Aula 305</span>
            </div>
          </div>
          <div class="materia-cupos"><strong>15</strong><small>cupos</small></div>
        </div>

      </div><!-- /insc-materias -->
    </div><!-- /insc-panel-left -->

    <!-- Panel derecho: resumen + horario -->
    <div class="insc-panel-right">

      <div class="insc-resumen">
        <div class="insc-resumen-header">Resumen de Inscripción</div>
        <div id="insc-seleccionadas">
          <div class="insc-empty">Ninguna materia seleccionada</div>
        </div>
        <div class="insc-resumen-footer">
          <div class="insc-uc-total">
            <span>Total UC:</span>
            <strong id="total-uc">0</strong>
          </div>
          <button class="btn-inscribir" id="btn-inscribir" onclick="confirmarInscripcion()" disabled>Confirmar Inscripción</button>
        </div>
      </div>

      <!-- Mini horario visual -->
      <div class="insc-horario-titulo">Vista previa del horario</div>
      <div class="insc-horario" id="horario-preview">
        <div class="horario-vacio">Selecciona materias para ver tu horario</div>
      </div>

    </div><!-- /insc-panel-right -->
  </div><!-- /insc-layout -->

  <!-- Historial de inscripciones -->
  <div class="exp-section-title" style="margin-top:32px">Historial de Inscripciones</div>
  <div class="panel">
    <div class="panel-header">
      <h4>Períodos anteriores</h4>
    </div>
    <div class="panel-body">
      <div class="historial-row">
        <div class="historial-term">Término I · 2024</div>
        <div class="historial-materias">Metodología de la Investigación, Gerencia Estratégica I, Estadística Aplicada</div>
        <div class="historial-badge aprobado">3 materias · 9 UC</div>
      </div>
      <div class="historial-row">
        <div class="historial-term">Término II · 2024</div>
        <div class="historial-materias">Gerencia Estratégica II, Derecho Administrativo, Finanzas Corporativas</div>
        <div class="historial-badge aprobado">3 materias · 9 UC</div>
      </div>
    </div>
  </div>

</div><!-- /tab-inscripciones -->

</div><!-- /tab-inscripciones -->
