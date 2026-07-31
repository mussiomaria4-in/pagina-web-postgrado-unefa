// Tweaks protocol
  window.addEventListener('message', e => {
    if (e.data?.type === '__activate_edit_mode')   document.getElementById('tweaks-panel').style.display = 'block';
    if (e.data?.type === '__deactivate_edit_mode') document.getElementById('tweaks-panel').style.display = 'none';
  });
  window.parent.postMessage({ type: '__edit_mode_available' }, '*');

  function applyTweaks() {
    const name    = document.getElementById('tw-name').value;
    const accent  = document.getElementById('tw-accent').value;
    const density = parseFloat(document.getElementById('tw-density').value);

    // Name
    document.querySelector('.welcome-bar-left h2').textContent = 'Bienvenido, ' + name;

    // Density
    document.documentElement.style.fontSize = (density * 16) + 'px';

    // Accent
    const cards = document.querySelectorAll('.module-card');
    if (accent === 'navy') {
      cards.forEach(c => { c.style.setProperty('--accent','#0d1b4b'); c.style.setProperty('--icon-bg','rgba(13,27,75,.08)'); });
    } else if (accent === 'red') {
      cards.forEach(c => { c.style.setProperty('--accent','#b91c1c'); c.style.setProperty('--icon-bg','rgba(185,28,28,.08)'); });
    } else {
      // restore original
      const accents = ['#0d1b4b','#b91c1c','#d4a017','#0d1b4b','#16a34a','#b91c1c'];
      const bgs     = ['rgba(13,27,75,.08)','rgba(185,28,28,.08)','rgba(212,160,23,.1)','rgba(13,27,75,.08)','rgba(34,197,94,.08)','rgba(185,28,28,.08)'];
      cards.forEach((c,i) => { c.style.setProperty('--accent',accents[i]); c.style.setProperty('--icon-bg',bgs[i]); });
    }
  }

  function descargarDoc(nombre) {
    alert('Generando ' + nombre + ' en PDF...\nEl archivo se descargará en unos segundos.');
  }

  function selBanco(btn, id) {
    document.querySelectorAll('.banco-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.banco-datos').forEach(d => d.style.display = 'none');
    btn.classList.add('active');
    document.getElementById('datos-' + id).style.display = 'block';
  }

  // ── INSCRIPCIONES ──
  const colores = ['#0d1b4b','#b91c1c','#d4a017','#16a34a','#7c3aed','#0891b2'];
  let materiasSeleccionadas = {};

  function toggleMateria(chk, codigo, nombre, uc, horario, profesor, seccion) {
    const card = chk.closest('.materia-card');
    if (chk.checked) {
      materiasSeleccionadas[codigo] = { nombre, uc, horario, profesor, seccion };
      card.classList.add('selected');
    } else {
      delete materiasSeleccionadas[codigo];
      card.classList.remove('selected');
    }
    renderResumen();
    renderHorario();
    updateUC();
  }

  function updateUC() {
    const total = Object.values(materiasSeleccionadas).reduce((s,m) => s + m.uc, 0);
    document.getElementById('uc-sel').textContent = total;
    document.getElementById('total-uc').textContent = total;
    const btn = document.getElementById('btn-inscribir');
    btn.disabled = total === 0;
  }

  function renderResumen() {
    const cont = document.getElementById('insc-seleccionadas');
    const keys = Object.keys(materiasSeleccionadas);
    if (keys.length === 0) {
      cont.innerHTML = '<div class="insc-empty">Ninguna materia seleccionada</div>';
      return;
    }
    cont.innerHTML = keys.map(k => {
      const m = materiasSeleccionadas[k];
      return `<div class="insc-sel-item">
        <span class="insc-sel-nombre">${m.nombre}</span>
        <span class="insc-sel-uc">${m.uc} UC</span>
      </div>`;
    }).join('');
  }

  function renderHorario() {
    const cont = document.getElementById('horario-preview');
    const keys = Object.keys(materiasSeleccionadas);
    if (keys.length === 0) {
      cont.innerHTML = '<div class="horario-vacio">Selecciona materias para ver tu horario</div>';
      return;
    }
    cont.innerHTML = keys.map((k, i) => {
      const m = materiasSeleccionadas[k];
      const color = colores[i % colores.length];
      return `<div class="horario-pill" style="background:${color}18; border-color:${color}; color:${color}">
        <span style="font-weight:800">${k}</span>
        <span>${m.horario}</span>
      </div>`;
    }).join('');
  }

  function filtrarMaterias(seccion) {
    document.querySelectorAll('.materia-card').forEach(card => {
      const s = card.dataset.seccion;
      card.style.display = (seccion === 'todas' || s === seccion) ? '' : 'none';
    });
  }

  function buscarMateria(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.materia-card').forEach(card => {
      const nombre = card.querySelector('.materia-nombre').textContent.toLowerCase();
      const codigo = card.dataset.codigo.toLowerCase();
      card.style.display = (nombre.includes(q) || codigo.includes(q)) ? '' : 'none';
    });
  }

  function confirmarInscripcion() {
    const keys = Object.keys(materiasSeleccionadas);
    if (keys.length === 0) return;
    const lista = keys.map(k => `• ${materiasSeleccionadas[k].nombre}`).join('\n');
    const total = Object.values(materiasSeleccionadas).reduce((s,m) => s + m.uc, 0);
    alert(`Inscripción enviada a revisión del coordinador.\n\nMaterias:\n${lista}\n\nTotal: ${total} UC\n\nRecibirá confirmación en su correo electrónico.`);
  }

  // Countdown
  (function updateCountdown() {
    const fin = new Date('2026-04-30T23:59:59');
    function tick() {
      const diff = fin - new Date();
      if (diff <= 0) return;
      const dias  = Math.floor(diff / 86400000);
      const horas = Math.floor((diff % 86400000) / 3600000);
      const mins  = Math.floor((diff % 3600000) / 60000);
      const dEl = document.getElementById('cd-dias');
      const hEl = document.getElementById('cd-horas');
      const mEl = document.getElementById('cd-mins');
      if (dEl) dEl.textContent = String(dias).padStart(2,'0');
      if (hEl) hEl.textContent = String(horas).padStart(2,'0');
      if (mEl) mEl.textContent = String(mins).padStart(2,'0');
    }
    tick();
    setInterval(tick, 60000);
  })();

  function showTerm(btn, termId) {
    document.querySelectorAll('.exp-term-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.exp-term-panel').forEach(p => p.style.display = 'none');
    btn.classList.add('active');
    document.getElementById(termId).style.display = 'block';
  }

  // ── ANIMACIONES: contador animado ──
  function animateCounters() {
    document.querySelectorAll('.exp-stat-val, .baremo-score-total span').forEach(el => {
      const text = el.textContent.trim();
      const num = parseFloat(text.replace(/[^0-9.]/g, ''));
      if (isNaN(num) || num === 0) return;
      const suffix = text.replace(/[0-9.]/g, '');
      const duration = 900;
      const start = performance.now();
      const isFloat = text.includes('.');
      function step(now) {
        const p = Math.min((now - start) / duration, 1);
        const ease = 1 - Math.pow(1 - p, 3);
        const val = isFloat ? (num * ease).toFixed(1) : Math.round(num * ease);
        el.textContent = val + suffix;
        if (p < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    });
  }

  // Re-trigger animations on tab switch
  function retriggerAnims(section) {
    section.querySelectorAll('.stat-card,.module-card,.exp-stat,.pago-stat,.materia-card,.doc-card,.arancel-row:not(.header-row),.historial-pago-row,.notas-table tbody tr').forEach(el => {
      el.style.animation = 'none';
      el.offsetHeight;
      el.style.animation = '';
    });
    setTimeout(animateCounters, 200);
  }

  // Tab navigation with content switching
  document.querySelectorAll('.nav-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      const target = tab.dataset.tab;
      document.querySelectorAll('.tab-content').forEach(c => {
        c.style.display = 'none';
        c.classList.remove('active');
      });
      const section = document.getElementById('tab-' + target);
      if (section) {
        section.style.display = 'block';
        section.classList.add('active');
        retriggerAnims(section);
      } else {
        const inicio = document.getElementById('tab-inicio');
        inicio.style.display = 'block';
        inicio.classList.add('active');
        retriggerAnims(inicio);
      }
    });
  });

  // Animar contadores al cargar
  animateCounters();

  // Multi-step form
  let pasoActual = 1;
  const formPreinscripcion = document.getElementById('form-preinscripcion');

  function obtenerCamposRequeridosPaso(paso) {
    const card = document.getElementById('paso-' + paso);
    if (!card) return [];

    const requeridos = [];
    card.querySelectorAll('.form-group label .req').forEach(reqTag => {
      const label = reqTag.closest('label');
      const formGroup = label ? label.closest('.form-group') : null;
      if (!formGroup) return;

      const field = formGroup.querySelector('input[name], select[name], textarea[name]');
      if (!field || !field.name) return;
      if (requeridos.some(item => item.field.name === field.name)) return;

      const labelTexto = label.textContent.replace('*', '').trim();
      requeridos.push({ field, labelTexto });
    });
    return requeridos;
  }

  function validarCamposPaso(paso) {
    const faltantes = [];
    const requeridos = obtenerCamposRequeridosPaso(paso);

    requeridos.forEach(({ field, labelTexto }) => {
      let valido = true;
      if (field.type === 'file') {
        valido = !!(field.files && field.files.length > 0);
      } else {
        valido = field.value.trim() !== '';
      }

      if (!valido) {
        faltantes.push(labelTexto);
      }
    });

    return faltantes;
  }

  function mostrarErrorValidacion(faltantes) {
    if (!faltantes.length) return;
    alert('Debes completar los campos obligatorios:\n\n• ' + faltantes.join('\n• '));
  }

  function irPaso(n) {
    if (n > pasoActual) {
      const faltantes = validarCamposPaso(pasoActual);
      if (faltantes.length > 0) {
        mostrarErrorValidacion(faltantes);
        return;
      }
    }

    document.getElementById('paso-' + pasoActual).style.display = 'none';
    // Mark done
    document.querySelectorAll('.step').forEach(s => {
      const sn = parseInt(s.dataset.step);
      s.classList.remove('active','done');
      if (sn < n) s.classList.add('done');
      if (sn === n) s.classList.add('active');
      // update circle for done
      if (sn < n) s.querySelector('.step-circle').textContent = '✓';
      else s.querySelector('.step-circle').textContent = sn;
    });
    pasoActual = n;
    document.getElementById('paso-' + n).style.display = 'block';
    if (n === 5) calcBaremo();
  }

  function updateFileName(input, labelId) {
    const label = document.getElementById(labelId);
    if (input.files && input.files[0]) {
      label.textContent = input.files[0].name;
      label.style.color = 'var(--navy)';
    } else {
      label.textContent = 'Sin archivos seleccionados';
      label.style.color = '';
    }
  }

  function enviarPreinscripcion() {
    alert('Pre-Inscripción enviada exitosamente.\nSu solicitud será revisada por la Coordinación de Postgrado.\nRecibirá confirmación en su correo electrónico registrado.');
  }

  if (formPreinscripcion) {
    formPreinscripcion.addEventListener('submit', function (event) {
      const faltantes = [];
      for (let paso = 1; paso <= 5; paso++) {
        faltantes.push(...validarCamposPaso(paso));
      }

      if (faltantes.length > 0) {
        event.preventDefault();
        mostrarErrorValidacion(Array.from(new Set(faltantes)));
      }
    });
  }
