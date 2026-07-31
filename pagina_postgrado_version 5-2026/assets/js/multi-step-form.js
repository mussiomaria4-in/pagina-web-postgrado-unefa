(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('multi-step-form');
    if (!form || form.getAttribute('data-registro-ajax') !== '1') {
      return;
    }

    var btn = document.getElementById('next-btn');
    var msg = document.getElementById('registro-mensaje');
    if (!btn) {
      return;
    }

    btn.addEventListener('click', function () {
      if (msg) {
        msg.style.display = 'none';
        msg.textContent = '';
      }

      var pwd = form.querySelector('#password');
      var cpwd = form.querySelector('#confirm_password');
      if (pwd && cpwd && pwd.value !== cpwd.value) {
        if (msg) {
          msg.style.display = 'block';
          msg.textContent = 'Las contraseñas no coinciden.';
        } else {
          window.alert('Las contraseñas no coinciden.');
        }
        return;
      }

      if (typeof form.reportValidity === 'function' && !form.reportValidity()) {
        return;
      }

      var fd = new FormData(form);
      var prevText = btn.textContent;
      btn.disabled = true;
      btn.textContent = 'Enviando…';

      fetch(form.getAttribute('action') || 'includes/procesar_registro.php', {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(function (response) {
          var ct = response.headers.get('content-type') || '';
          if (ct.indexOf('application/json') === -1) {
            return response.text().then(function (t) {
              throw new Error(t ? 'Respuesta inesperada del servidor.' : 'Respuesta inesperada del servidor.');
            });
          }
          return response.json();
        })
        .then(function (data) {
          if (!data) {
            return;
          }
          if (data.status === 'ok' && data.redirect) {
            window.location.href = data.redirect;
            return;
          }
          if (data.status === 'error') {
            var text = data.message || 'No se pudo completar el registro.';
            if (msg) {
              msg.style.display = 'block';
              msg.textContent = text;
            } else {
              window.alert(text);
            }
          }
        })
        .catch(function (err) {
          var text = (err && err.message) ? err.message : 'Error de conexión. Intente de nuevo.';
          if (msg) {
            msg.style.display = 'block';
            msg.textContent = text;
          } else {
            window.alert(text);
          }
        })
        .finally(function () {
          btn.disabled = false;
          btn.textContent = prevText;
        });
    });
  });
})();
