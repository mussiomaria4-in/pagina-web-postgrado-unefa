<!-- ══════════════════════════════════════════════════
     TAB: PAGOS
══════════════════════════════════════════════════ -->
<div id="tab-pagos" class="tab-content" style="display:none">

  <!-- Estado de solvencia -->
  <div class="pagos-solvencia-banner solvente">
    <div class="solvencia-left">
      <div class="solvencia-icon">✔</div>
      <div>
        <h2>Estudiante Solvente</h2>
        <p>Todos los aranceles del período vigente han sido cancelados y verificados.</p>
      </div>
    </div>
    <div class="solvencia-right">
      <button class="exp-btn exp-btn-solid" onclick="alert('Generando constancia de solvencia...')">Descargar Solvencia</button>
    </div>
  </div>

  <!-- Resumen de pagos -->
  <div class="pagos-stats">
    <div class="pago-stat">
      <div class="pago-stat-icon" style="background:rgba(22,163,74,.1);color:#16a34a">✔</div>
      <div>
        <strong>3</strong>
        <span>Pagos registrados</span>
      </div>
    </div>
    <div class="pago-stat">
      <div class="pago-stat-icon" style="background:rgba(13,27,75,.08);color:var(--navy)">💰</div>
      <div>
        <strong>Bs. 450.00</strong>
        <span>Total cancelado</span>
      </div>
    </div>
    <div class="pago-stat">
      <div class="pago-stat-icon" style="background:rgba(212,160,23,.1);color:var(--gold)">📋</div>
      <div>
        <strong>0</strong>
        <span>Pagos pendientes</span>
      </div>
    </div>
    <div class="pago-stat">
      <div class="pago-stat-icon" style="background:rgba(185,28,28,.08);color:var(--red)">📅</div>
      <div>
        <strong>Término IV</strong>
        <span>Próximo arancel</span>
      </div>
    </div>
  </div>

  <!-- Layout: aranceles pendientes + registrar pago -->
  <div class="pagos-layout">

    <!-- Izquierda: aranceles y historial -->
    <div class="pagos-left">

      <!-- Aranceles del período vigente -->
      <div class="exp-section-title">Aranceles — Término III · 2025</div>
      <div class="aranceles-tabla">
        <div class="arancel-row header-row">
          <span>Concepto</span>
          <span>Monto (Bs.)</span>
          <span>Fecha límite</span>
          <span>Estado</span>
        </div>
        <div class="arancel-row">
          <span>Arancel de Inscripción</span>
          <span class="arancel-monto">150.00</span>
          <span>30/04/2026</span>
          <span><span class="estado-badge aprobado">Pagado</span></span>
        </div>
        <div class="arancel-row">
          <span>Material de Estudio</span>
          <span class="arancel-monto">50.00</span>
          <span>30/04/2026</span>
          <span><span class="estado-badge aprobado">Pagado</span></span>
        </div>
        <div class="arancel-row">
          <span>Otros Aranceles</span>
          <span class="arancel-monto">25.00</span>
          <span>30/04/2026</span>
          <span><span class="estado-badge aprobado">Pagado</span></span>
        </div>
      </div>

      <!-- Próximos aranceles -->
      <div class="exp-section-title" style="margin-top:24px">Próximos Aranceles</div>
      <div class="aranceles-tabla">
        <div class="arancel-row header-row">
          <span>Concepto</span>
          <span>Monto (Bs.)</span>
          <span>Fecha límite</span>
          <span>Estado</span>
        </div>
        <div class="arancel-row">
          <span>Arancel de Inscripción — Término IV</span>
          <span class="arancel-monto">150.00</span>
          <span>31/07/2026</span>
          <span><span class="estado-badge en-curso">Pendiente</span></span>
        </div>
        <div class="arancel-row">
          <span>Arancel de Grado</span>
          <span class="arancel-monto">200.00</span>
          <span>Por definir</span>
          <span><span class="estado-badge en-curso">Pendiente</span></span>
        </div>
        <div class="arancel-row">
          <span>Constancias y Certificados</span>
          <span class="arancel-monto">30.00</span>
          <span>A solicitud</span>
          <span><span class="estado-badge en-curso">Pendiente</span></span>
        </div>
      </div>

      <!-- Historial de pagos -->
      <div class="exp-section-title" style="margin-top:24px">Historial de Pagos</div>
      <div class="panel">
        <div class="panel-body">
          <div class="historial-pago-row">
            <div class="hp-fecha">15/02/2026</div>
            <div class="hp-info">
              <strong>Arancel de Inscripción — Término III</strong>
              <span>Transferencia · Banco de Venezuela · Ref: 00123456</span>
            </div>
            <div class="hp-monto">Bs. 150.00</div>
            <div class="hp-estado"><span class="estado-badge aprobado">Verificado</span></div>
            <button class="hp-recibo" onclick="alert('Descargando recibo...')">↓ Recibo</button>
          </div>
          <div class="historial-pago-row">
            <div class="hp-fecha">16/02/2026</div>
            <div class="hp-info">
              <strong>Material de Estudio — Término III</strong>
              <span>Pago móvil · Banesco · Ref: 98712340</span>
            </div>
            <div class="hp-monto">Bs. 50.00</div>
            <div class="hp-estado"><span class="estado-badge aprobado">Verificado</span></div>
            <button class="hp-recibo" onclick="alert('Descargando recibo...')">↓ Recibo</button>
          </div>
          <div class="historial-pago-row">
            <div class="hp-fecha">17/02/2026</div>
            <div class="hp-info">
              <strong>Otros Aranceles — Término III</strong>
              <span>Depósito bancario · BANFANB · Ref: 55443322</span>
            </div>
            <div class="hp-monto">Bs. 25.00</div>
            <div class="hp-estado"><span class="estado-badge aprobado">Verificado</span></div>
            <button class="hp-recibo" onclick="alert('Descargando recibo...')">↓ Recibo</button>
          </div>
        </div>
      </div>

    </div><!-- /pagos-left -->

    <!-- Derecha: registrar nuevo pago -->
    <div class="pagos-right">

      <div class="registrar-pago-card">
        <div class="rp-header">Registrar Comprobante de Pago</div>
        <div class="rp-body">

          <!-- Datos bancarios -->
          <div class="datos-bancarios">
            <div class="db-title">Datos para realizar el pago</div>

            <div class="banco-tab-row">
              <button class="banco-tab active" onclick="selBanco(this,'bv')">Banco Venezuela</button>
              <button class="banco-tab" onclick="selBanco(this,'banesco')">Banesco</button>
              <button class="banco-tab" onclick="selBanco(this,'banfanb')">BANFANB</button>
            </div>

            <div id="datos-bv" class="banco-datos">
              <div class="db-row"><span>Banco</span><strong>Banco de Venezuela</strong></div>
              <div class="db-row"><span>Cuenta corriente</span><strong>0102-0000-00-0000000001</strong></div>
              <div class="db-row"><span>RIF</span><strong>G-20005482-1</strong></div>
              <div class="db-row"><span>A nombre de</span><strong>UNEFA Postgrado</strong></div>
            </div>
            <div id="datos-banesco" class="banco-datos" style="display:none">
              <div class="db-row"><span>Banco</span><strong>Banesco</strong></div>
              <div class="db-row"><span>Cuenta corriente</span><strong>0134-0000-00-0000000002</strong></div>
              <div class="db-row"><span>RIF</span><strong>G-20005482-1</strong></div>
              <div class="db-row"><span>A nombre de</span><strong>UNEFA Postgrado</strong></div>
            </div>
            <div id="datos-banfanb" class="banco-datos" style="display:none">
              <div class="db-row"><span>Banco</span><strong>BANFANB</strong></div>
              <div class="db-row"><span>Cuenta corriente</span><strong>0151-0000-00-0000000003</strong></div>
              <div class="db-row"><span>RIF</span><strong>G-20005482-1</strong></div>
              <div class="db-row"><span>A nombre de</span><strong>UNEFA Postgrado</strong></div>
            </div>
          </div>

          <!-- Formulario -->
          <div class="form-group" style="margin-top:16px">
            <label>Concepto de pago <span class="req">*</span></label>
            <select>
              <option value="">Seleccione</option>
              <option>Arancel de Inscripción</option>
              <option>Arancel de Grado</option>
              <option>Constancias y Certificados</option>
              <option>Material de Estudio</option>
              <option>Otros Aranceles</option>
            </select>
          </div>
          <div class="form-group">
            <label>Banco donde realizó el pago <span class="req">*</span></label>
            <select>
              <option value="">Seleccione</option>
              <option>Banco de Venezuela</option>
              <option>Banesco</option>
              <option>BANFANB</option>
            </select>
          </div>
          <div class="form-group">
            <label>Método de pago <span class="req">*</span></label>
            <select>
              <option value="">Seleccione</option>
              <option>Transferencia bancaria</option>
              <option>Pago móvil</option>
              <option>Depósito bancario</option>
            </select>
          </div>
          <div class="form-grid-2" style="gap:10px">
            <div class="form-group">
              <label>Monto (Bs.) <span class="req">*</span></label>
              <input type="number" placeholder="0.00" step="0.01" min="0" />
            </div>
            <div class="form-group">
              <label>Fecha del pago <span class="req">*</span></label>
              <input type="date" />
            </div>
          </div>
          <div class="form-group">
            <label>N° de referencia <span class="req">*</span></label>
            <input type="text" placeholder="Ej: 00123456" />
          </div>
          <div class="form-group">
            <label>Adjuntar comprobante <span class="req">*</span></label>
            <div class="file-upload-box">
              <input type="file" accept=".jpg,.png,.pdf" id="file-comprobante" onchange="updateFileName(this,'lbl-comprobante')" />
              <label for="file-comprobante" class="file-upload-label">
                <span class="file-btn">Seleccionar archivo</span>
                <span class="file-name" id="lbl-comprobante">Sin archivo seleccionado</span>
              </label>
              <small>JPG, PNG o PDF</small>
            </div>
          </div>
          <button class="btn-inscribir" style="width:100%;margin-top:8px" onclick="alert('Comprobante enviado exitosamente. Será verificado en un plazo de 24-48 horas hábiles.')">Enviar Comprobante</button>
        </div>
      </div>

    </div><!-- /pagos-right -->
  </div><!-- /pagos-layout -->

</div><!-- /tab-pagos -->

</div><!-- /tab-pagos -->
