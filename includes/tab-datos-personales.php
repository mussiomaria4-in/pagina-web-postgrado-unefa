<!-- ── PASO 1: Datos Personales ── -->
<?php
$nombreCompleto = isset($estudiante['nombre_completo']) ? (string) $estudiante['nombre_completo'] : '';
$partesNombre = preg_split('/\s+/', trim($nombreCompleto));
$primerNombreDefault = isset($partesNombre[0]) ? $partesNombre[0] : '';
$segundoNombreDefault = isset($partesNombre[1]) ? $partesNombre[1] : '';
$primerApellidoDefault = isset($partesNombre[2]) ? $partesNombre[2] : '';
$segundoApellidoDefault = isset($partesNombre[3]) ? $partesNombre[3] : '';
$cedulaDefault = isset($estudiante['ci']) ? preg_replace('/\D/', '', (string) $estudiante['ci']) : '';
?>
  <div class="form-card" id="paso-1">
    <div class="form-card-header">
      <span class="form-step-badge">Paso 1 de 5</span>
      <h3>Datos Personales</h3>
    </div>
    <div class="form-body">
      <div class="form-grid-2">
        <div class="form-group">
          <label>Tipo de documento <span class="req">*</span></label>
          <select name="tipoDocumento">
            <option value="V">V — Venezolano</option>
            <option value="E">E — Extranjero</option>
          </select>
        </div>
        <div class="form-group">
          <label>Cédula de Identidad <span class="req">*</span></label>
          <input type="text" name="cedula" value="<?php echo htmlspecialchars((string) (isset($datos_form['cedula']) ? $datos_form['cedula'] : $cedulaDefault), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ej: 28310291" />
        </div>
        <div class="form-group">
          <label>Primer nombre <span class="req">*</span></label>
          <input type="text" name="primerNombre" value="<?php echo htmlspecialchars((string) (isset($datos_form['primerNombre']) ? $datos_form['primerNombre'] : $primerNombreDefault), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Escribe tu primer nombre" />
        </div>
        <div class="form-group">
          <label>Segundo nombre</label>
          <input type="text" name="segundoNombre" value="<?php echo htmlspecialchars((string) (isset($datos_form['segundoNombre']) ? $datos_form['segundoNombre'] : $segundoNombreDefault), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Escribe tu segundo nombre" />
        </div>
        <div class="form-group">
          <label>Primer apellido <span class="req">*</span></label>
          <input type="text" name="primerApellido" value="<?php echo htmlspecialchars((string) (isset($datos_form['primerApellido']) ? $datos_form['primerApellido'] : $primerApellidoDefault), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Escribe tu primer apellido" />
        </div>
        <div class="form-group">
          <label>Segundo apellido <span class="req">*</span></label>
          <input type="text" name="segundoApellido" value="<?php echo htmlspecialchars((string) (isset($datos_form['segundoApellido']) ? $datos_form['segundoApellido'] : $segundoApellidoDefault), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Escribe tu segundo apellido" />
        </div>
        <div class="form-group">
          <label>Fecha de nacimiento <span class="req">*</span></label>
          <input type="date" name="fechaNacimiento" value="<?php echo htmlspecialchars((string) (isset($datos_form['fechaNacimiento']) ? $datos_form['fechaNacimiento'] : ''), ENT_QUOTES, 'UTF-8'); ?>" />
        </div>
        <div class="form-group">
          <label>Sexo <span class="req">*</span></label>
          <select name="sexo">
            <option value="">Seleccione</option>
            <option>Femenino</option>
            <option>Masculino</option>
          </select>
        </div>
        <div class="form-group full">
          <label>Estado civil <span class="req">*</span></label>
          <select name="estadoCivil">
            <option value="">Seleccione</option>
            <option>Soltero/a</option>
            <option>Casado/a</option>
            <option>Divorciado/a</option>
            <option>Viudo/a</option>
            <option>Unión estable de hecho</option>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <span></span>
        <button class="btn-siguiente" type="button" onclick="irPaso(2)">Siguiente →</button>
      </div>
    </div>
  </div>
