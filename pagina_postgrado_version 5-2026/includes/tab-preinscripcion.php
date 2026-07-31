<?php
if (!isset($datos_form) || !is_array($datos_form)) {
    $datos_form = array();
}
if (!isset($preguntas_por_categoria) || !is_array($preguntas_por_categoria)) {
    $preguntas_por_categoria = array();
}

if (!function_exists('baremo_texto_fila')) {
    /**
     * @param array $row fila de baremo_preguntas (columnas pueden variar)
     */
    function baremo_texto_fila(array $row)
    {
        foreach (array('pregunta', 'texto', 'descripcion', 'titulo', 'enunciado') as $k) {
            if (!empty($row[$k])) {
                return (string) $row[$k];
            }
        }
        return '';
    }
}

if (!function_exists('baremo_nombre_input')) {
    /**
     * @param array  $row
     * @param string $categoria
     * @param int    $indice orden en la lista (respaldo si no hay id)
     */
    function baremo_nombre_input(array $row, $categoria, $indice)
    {
        if (isset($row['id']) && (string) $row['id'] !== '') {
            return 'baremo_' . (int) $row['id'];
        }
        $slug = preg_replace('/[^a-zA-Z0-9_]/', '_', (string) $categoria);

        return 'baremo_' . $slug . '_' . (int) $indice;
    }
}
?>
<!-- ══════════════════════════════════════════════════
     TAB: PRE-INSCRIPCIÓN / BAREMO
══════════════════════════════════════════════════ -->
<div id="tab-preinscripcion" class="tab-content" style="display:none">

  <!-- Intro -->
  <div class="preinsc-intro">
    <div class="preinsc-intro-icon">📋</div>
    <div>
      <h2>Preinscripción SIP-Postgrado</h2>
      <p>Complete los campos a continuación. Este formulario está dirigido a aspirantes que <strong>no son estudiantes regulares</strong> de la UNEFA.</p>
    </div>
  </div>

  <!-- Stepper -->
  <div class="stepper">
    <div class="step active" data-step="1"><div class="step-circle">1</div><span>Datos Personales</span></div>
    <div class="step-line"></div>
    <div class="step" data-step="2"><div class="step-circle">2</div><span>Dirección</span></div>
    <div class="step-line"></div>
    <div class="step" data-step="3"><div class="step-circle">3</div><span>Contacto</span></div>
    <div class="step-line"></div>
    <div class="step" data-step="4"><div class="step-circle">4</div><span>Académico / Laboral</span></div>
    <div class="step-line"></div>
    <div class="step" data-step="5"><div class="step-circle">5</div><span>Entrevista / Docs</span></div>
  </div>

  <?php if (isset($lista_errores) && !empty($lista_errores)) { ?>
    <div style="margin:14px 0;padding:12px 14px;border:1px solid #b91c1c;border-radius:8px;background:#fee2e2;color:#7f1d1d;">
      <strong>Se encontraron errores en el formulario:</strong>
      <ul style="margin:8px 0 0 18px;">
        <?php foreach ($lista_errores as $mensaje) { ?>
          <li><?php echo htmlspecialchars((string) $mensaje, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>

  <form method="POST" action="index.php" enctype="multipart/form-data" id="form-preinscripcion">

  <?php include_once __DIR__ . '/tab-datos-personales.php'; ?>

  <!-- ── PASO 2: Dirección ── -->
  <div class="form-card" id="paso-2" style="display:none">
    <div class="form-card-header">
      <span class="form-step-badge">Paso 2 de 5</span>
      <h3>Dirección de habitación</h3>
    </div>
    <div class="form-body">
      <div class="form-grid-2">
        <div class="form-group">
          <label>Estado <span class="req">*</span></label>
          <select name="estadoHabitacion">
            <option value="">Seleccione</option>
            <option>Amazonas</option><option>Anzoátegui</option><option>Apure</option>
            <option>Aragua</option><option>Barinas</option><option>Bolívar</option>
            <option>Carabobo</option><option>Cojedes</option><option>Delta Amacuro</option>
            <option>Distrito Capital</option><option>Falcón</option><option>Guárico</option>
            <option>Lara</option><option>Mérida</option><option>Miranda</option>
            <option>Monagas</option><option>Nueva Esparta</option><option>Portuguesa</option>
            <option>Sucre</option><option>Táchira</option><option>Trujillo</option>
            <option>Vargas</option><option>Yaracuy</option><option>Zulia</option>
          </select>
        </div>
        <div class="form-group">
          <label>Municipio <span class="req">*</span></label>
          <select name="municipioHabitacion">
            <option value="">Seleccione</option>
            <option>Libertador</option><option>Sucre</option><option>Baruta</option>
            <option>Chacao</option><option>El Hatillo</option><option>Otro</option>
          </select>
        </div>
        <div class="form-group full">
          <label>Ciudad / Pueblo <span class="req">*</span></label>
          <input type="text" name="ciudadHabitacion" value="<?php echo htmlspecialchars((string) (isset($datos_form['ciudadHabitacion']) ? $datos_form['ciudadHabitacion'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Escribe tu ciudad o pueblo" />
        </div>
        <div class="form-group">
          <label>Avenida / Calle / Vereda <span class="req">*</span></label>
          <input type="text" name="avenidaCalle" value="<?php echo htmlspecialchars((string) (isset($datos_form['avenidaCalle']) ? $datos_form['avenidaCalle'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Escribe tu avenida o calle" />
        </div>
        <div class="form-group">
          <label>Urbanización / Barrio / Sector</label>
          <input type="text" name="urbanizacionBarrio" value="<?php echo htmlspecialchars((string) (isset($datos_form['urbanizacionBarrio']) ? $datos_form['urbanizacionBarrio'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Escribe tu urbanización o barrio" />
        </div>
        <div class="form-group">
          <label>Tipo de residencia <span class="req">*</span></label>
          <select name="tipoResidencia">
            <option value="">Seleccione</option>
            <option>Casa</option>
            <option>Apartamento</option>
            <option>Quinta</option>
            <option>Otro</option>
          </select>
        </div>
        <div class="form-group">
          <label>Residencia (nombre o N° casa / edificio)</label>
          <input type="text" name="residencia" value="<?php echo htmlspecialchars((string) (isset($datos_form['residencia']) ? $datos_form['residencia'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Casa o edificio" />
        </div>
      </div>
      <div class="form-actions">
        <button class="btn-anterior" type="button" onclick="irPaso(1)">← Atrás</button>
        <button class="btn-siguiente" type="button" onclick="irPaso(3)">Siguiente →</button>
      </div>
    </div>
  </div>

  <!-- ── PASO 3: Redes + Contacto ── -->
  <div class="form-card" id="paso-3" style="display:none">
    <div class="form-card-header">
      <span class="form-step-badge">Paso 3 de 5</span>
      <h3>Redes sociales y Contacto</h3>
    </div>
    <div class="form-body">
      <h4 class="sub-section">Redes Sociales</h4>
      <div class="form-grid-2">
        <div class="form-group">
          <label>Twitter (X)</label>
          <div class="input-icon-row"><span class="input-icon">𝕏</span><input type="text" name="twitter" value="<?php echo htmlspecialchars((string) (isset($datos_form['twitter']) ? $datos_form['twitter'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Tu usuario" /></div>
        </div>
        <div class="form-group">
          <label>Facebook</label>
          <div class="input-icon-row"><span class="input-icon">f</span><input type="text" name="facebook" value="<?php echo htmlspecialchars((string) (isset($datos_form['facebook']) ? $datos_form['facebook'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Tu usuario" /></div>
        </div>
        <div class="form-group">
          <label>Instagram</label>
          <div class="input-icon-row"><span class="input-icon">◎</span><input type="text" name="instagram" value="<?php echo htmlspecialchars((string) (isset($datos_form['instagram']) ? $datos_form['instagram'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Tu usuario" /></div>
        </div>
        <div class="form-group">
          <label>LinkedIn</label>
          <div class="input-icon-row"><span class="input-icon">in</span><input type="text" name="linkedin" value="<?php echo htmlspecialchars((string) (isset($datos_form['linkedin']) ? $datos_form['linkedin'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Tu usuario" /></div>
        </div>
      </div>

      <h4 class="sub-section">Contacto y condición</h4>
      <div class="form-grid-2">
        <div class="form-group">
          <label>Teléfono fijo <span class="req">*</span></label>
          <div class="input-icon-row"><span class="input-icon">☎</span><input type="tel" name="telefono" value="<?php echo htmlspecialchars((string) (isset($datos_form['telefono']) ? $datos_form['telefono'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="11 dígitos, inicia con 0" /></div>
        </div>
        <div class="form-group">
          <label>Celular <span class="req">*</span></label>
          <div class="input-icon-row"><span class="input-icon">📱</span><input type="tel" name="celular" value="<?php echo htmlspecialchars((string) (isset($datos_form['celular']) ? $datos_form['celular'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="11 dígitos, inicia con 0" /></div>
        </div>
        <div class="form-group">
          <label>Condición de ingreso <span class="req">*</span></label>
          <select name="condicion">
            <option value="">Seleccione</option>
            <option>Nuevo ingreso</option>
            <option>Reingreso</option>
            <option>Equivalencia</option>
            <option>Traslado</option>
          </select>
        </div>
        <div class="form-group">
          <label>Condición del usuario <span class="req">*</span></label>
          <select name="condicionUsuario">
            <option value="">Seleccione</option>
            <option>Civil</option>
            <option>Militar activo</option>
            <option>Militar retirado</option>
            <option>Funcionario público</option>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <button class="btn-anterior" type="button" onclick="irPaso(2)">← Atrás</button>
        <button class="btn-siguiente" type="button" onclick="irPaso(4)">Siguiente →</button>
      </div>
    </div>
  </div>

  <!-- ── PASO 4: Académico + Laboral ── -->
  <div class="form-card" id="paso-4" style="display:none">
    <div class="form-card-header">
      <span class="form-step-badge">Paso 4 de 5</span>
      <h3>Datos Académicos y Laborales</h3>
    </div>
    <div class="form-body">
      <h4 class="sub-section">Datos Académicos</h4>
      <div class="form-grid-2">
        <div class="form-group">
          <label>Área de conocimiento <span class="req">*</span></label>
          <input type="text" name="areaConocimiento" value="<?php echo htmlspecialchars((string) (isset($datos_form['areaConocimiento']) ? $datos_form['areaConocimiento'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Área" />
        </div>
        <div class="form-group">
          <label>Nivel académico <span class="req">*</span></label>
          <input type="text" name="nivelAcademico" value="<?php echo htmlspecialchars((string) (isset($datos_form['nivelAcademico']) ? $datos_form['nivelAcademico'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ej: Pregrado, Especialización" />
        </div>
        <div class="form-group">
          <label>Universidad <span class="req">*</span></label>
          <input type="text" name="universidad" value="<?php echo htmlspecialchars((string) (isset($datos_form['universidad']) ? $datos_form['universidad'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Universidad" />
        </div>
        <div class="form-group">
          <label>Título obtenido <span class="req">*</span></label>
          <input type="text" name="tituloAcademico" value="<?php echo htmlspecialchars((string) (isset($datos_form['tituloAcademico']) ? $datos_form['tituloAcademico'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Título" />
        </div>
        <div class="form-group">
          <label>Año de graduación <span class="req">*</span></label>
          <select name="anoGraduacion">
            <option value="">Seleccione</option>
            <option>2026</option><option>2025</option><option>2024</option><option>2023</option>
            <option>2022</option><option>2021</option><option>2020</option><option>2019</option>
            <option>2018</option><option>2017</option><option>2016</option><option>2015</option>
            <option>2014</option><option>2013</option><option>2012</option><option>2010</option>
            <option>Antes de 2010</option>
          </select>
        </div>
        <div class="form-group">
          <label>Promedio de calificaciones <span class="req">*</span></label>
          <input type="number" name="promedio" value="<?php echo htmlspecialchars((string) (isset($datos_form['promedio']) ? $datos_form['promedio'] : (isset($estudiante['promedio_general']) ? $estudiante['promedio_general'] : '')), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ej: 16 o 16.5" min="0" max="20" step="0.1" />
        </div>
      </div>

      <h4 class="sub-section">Datos Laborales</h4>
      <div class="form-grid-2">
        <div class="form-group">
          <label>Tipo de institución <span class="req">*</span></label>
          <select name="tipoInstitucion">
            <option value="">Seleccione</option>
            <option>Pública</option>
            <option>Privada</option>
            <option>Mixta</option>
            <option>ONG</option>
            <option>Fuerzas Armadas</option>
            <option>Otro</option>
          </select>
        </div>
        <div class="form-group">
          <label>Nombre de la institución u organismo <span class="req">*</span></label>
          <input type="text" name="nombreInstitucion" value="<?php echo htmlspecialchars((string) (isset($datos_form['nombreInstitucion']) ? $datos_form['nombreInstitucion'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nombre" />
        </div>
        <div class="form-group">
          <label>Antigüedad <span class="req">*</span></label>
          <input type="text" name="antiguedad" value="<?php echo htmlspecialchars((string) (isset($datos_form['antiguedad']) ? $datos_form['antiguedad'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Años / meses" />
        </div>
        <div class="form-group">
          <label>Teléfono (trabajo)</label>
          <input type="tel" name="telefonoTrabajo" value="<?php echo htmlspecialchars((string) (isset($datos_form['telefonoTrabajo']) ? $datos_form['telefonoTrabajo'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Opcional, 11 dígitos si indica" />
        </div>
        <div class="form-group">
          <label>Cargo <span class="req">*</span></label>
          <input type="text" name="cargo" value="<?php echo htmlspecialchars((string) (isset($datos_form['cargo']) ? $datos_form['cargo'] : ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Cargo" />
        </div>
        <div class="form-group">
          <label>¿Trabaja en la UNEFA? <span class="req">*</span></label>
          <select name="trabajaUnefa">
            <option value="">Seleccione</option>
            <option value="Sí">Sí</option>
            <option value="No">No</option>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <button class="btn-anterior" type="button" onclick="irPaso(3)">← Atrás</button>
        <button class="btn-siguiente" type="button" onclick="irPaso(5)">Siguiente →</button>
      </div>
    </div>
  </div>

  <!-- ── PASO 5: Entrevista / Baremo / Documentos ── -->
  <div class="form-card" id="paso-5" style="display:none">
    <div class="form-card-header">
      <span class="form-step-badge">Paso 5 de 5</span>
      <h3>Aspectos para la Entrevista</h3>
      <p>Responda Sí o No según corresponda a su perfil</p>
    </div>
    <div class="form-body">

      <!-- Tabla baremo -->
      <div class="baremo-table-wrap">
        <table class="baremo-table">
          <thead>
            <tr>
              <th>Aspectos a Evaluar</th>
              <th>Sí</th>
              <th>No</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($preguntas_por_categoria)) { ?>
              <?php foreach ($preguntas_por_categoria as $categoria => $filas) { ?>
                <tr class="baremo-category"><td colspan="3"><?php echo htmlspecialchars((string) $categoria, ENT_QUOTES, 'UTF-8'); ?></td></tr>
                <?php
                $i = 0;
                foreach ($filas as $row) {
                    if (!is_array($row)) {
                        continue;
                    }
                    $i++;
                    $txt = baremo_texto_fila($row);
                    if ($txt === '') {
                        $txt = 'Pregunta ' . $i;
                    }
                    $inputName = baremo_nombre_input($row, (string) $categoria, isset($row['orden']) ? (int) $row['orden'] : $i);
                ?>
                <tr>
                  <td><?php echo htmlspecialchars($txt, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><input type="radio" name="<?php echo htmlspecialchars($inputName, ENT_QUOTES, 'UTF-8'); ?>" value="si" /></td>
                  <td><input type="radio" name="<?php echo htmlspecialchars($inputName, ENT_QUOTES, 'UTF-8'); ?>" value="no" checked /></td>
                </tr>
                <?php
                }
                ?>
              <?php } ?>
            <?php } else { ?>
              <tr>
                <td colspan="3" style="padding:16px;color:var(--muted);">
                  No hay preguntas de baremo disponibles. Verifique la tabla <code>baremo_preguntas</code> en la base de datos o la conexión.
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <!-- Texto libre -->
      <div class="form-group" style="margin-top:20px">
        <label>Debe explicar: Tema de interés específico para su investigación vinculados a las áreas prioritarias de desarrollo de la nación: <span class="req">*</span></label>
        <textarea name="temaInvestigacion" rows="4" placeholder="Describa su tema de interés investigativo..."><?php echo htmlspecialchars((string) (isset($datos_form['temaInvestigacion']) ? $datos_form['temaInvestigacion'] : ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
      </div>

      <!-- Otros datos -->
      <h4 class="sub-section">Otros datos</h4>
      <div class="form-grid-2">
        <div class="form-group">
          <label>¿Cuenta con beca?</label>
          <select name="tipoBeca">
            <option value="">Seleccione</option>
            <option>No</option>
            <option>Beca MPPEU</option>
            <option>Beca UNEFA</option>
            <option>Beca Fundayacucho</option>
            <option>Otra beca</option>
          </select>
        </div>
        <div class="form-group">
          <label>Fecha de ingreso a la UNEFA</label>
          <input type="date" name="fechaIngresoUnefa" value="<?php echo htmlspecialchars((string) (isset($datos_form['fechaIngresoUnefa']) ? $datos_form['fechaIngresoUnefa'] : ''), ENT_QUOTES, 'UTF-8'); ?>" />
        </div>
      </div>

      <!-- Adjuntar documentos -->
      <h4 class="sub-section">Adjuntar Documentos</h4>
      <div class="docs-instructions">
        <strong>Instrucciones:</strong>
        <ul>
          <li>Solo se aceptan archivos de imagen en formato JPG o PNG.</li>
          <li>Las imágenes deben ser en Color, Claras y Legibles.</li>
          <li>La resolución recomendada es de 1400 x 1400.</li>
        </ul>
      </div>
      <div class="form-grid-2" style="margin-top:14px">
        <div class="form-group">
          <label>Documento de Identidad <span class="req">*</span></label>
          <div class="file-upload-box">
            <input type="file" name="archivo_ci" accept=".jpg,.png" id="file-ci" onchange="updateFileName(this,'lbl-ci')" />
            <label for="file-ci" class="file-upload-label">
              <span class="file-btn">Seleccionar archivo</span>
              <span class="file-name" id="lbl-ci">Sin archivos seleccionados</span>
            </label>
            <small>Solo se aceptan formatos JPG y PNG</small>
          </div>
        </div>
        <div class="form-group">
          <label>Título <span class="req">*</span></label>
          <div class="file-upload-box">
            <input type="file" name="archivo_titulo" accept=".jpg,.png" id="file-titulo" onchange="updateFileName(this,'lbl-titulo')" />
            <label for="file-titulo" class="file-upload-label">
              <span class="file-btn">Seleccionar archivo</span>
              <span class="file-name" id="lbl-titulo">Sin archivos seleccionados</span>
            </label>
            <small>Solo se aceptan formatos JPG y PNG</small>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button class="btn-anterior" type="button" onclick="irPaso(4)">← Atrás</button>
        <button class="btn-enviar" type="submit">Finalizar Registro</button>
      </div>
    </div>
  </div>

  </form>

</div><!-- /tab-preinscripcion -->
