<?php
if (!function_exists('app_url')) {
    require_once __DIR__ . '/paths_helper.php';
}
$logo_url = app_url('assets/imagenes/LOGO-1-1.png');
?>
<!-- HEADER -->
<header>
  <div class="header-bg"></div>
  <!-- Partículas flotantes -->
  <div class="header-particles">
    <div class="hparticle" style="left:8%;top:20%;width:6px;height:6px;animation-delay:0s;animation-duration:6s"></div>
    <div class="hparticle" style="left:25%;top:60%;width:4px;height:4px;animation-delay:1s;animation-duration:8s"></div>
    <div class="hparticle" style="left:50%;top:30%;width:8px;height:8px;animation-delay:2s;animation-duration:7s"></div>
    <div class="hparticle" style="left:70%;top:70%;width:5px;height:5px;animation-delay:.5s;animation-duration:9s"></div>
    <div class="hparticle" style="left:85%;top:25%;width:6px;height:6px;animation-delay:1.5s;animation-duration:6.5s"></div>
    <div class="hparticle gold" style="left:40%;top:75%;width:5px;height:5px;animation-delay:3s;animation-duration:7.5s"></div>
    <div class="hparticle gold" style="left:90%;top:55%;width:4px;height:4px;animation-delay:.8s;animation-duration:8.5s"></div>
    <div class="hparticle red" style="left:15%;top:80%;width:5px;height:5px;animation-delay:2.2s;animation-duration:7s"></div>
    <div class="hparticle red" style="left:60%;top:15%;width:4px;height:4px;animation-delay:1.8s;animation-duration:9s"></div>
  </div>
  <div class="header-inner">
    <div class="header-brand">
      <img src="<?= htmlspecialchars($logo_url, ENT_QUOTES, 'UTF-8') ?>" alt="Logo UNEFA" />
      <div class="header-brand-text">
        <strong>Universidad Nacional Experimental Politécnica</strong>
        <span>de la Fuerza Armada Nacional Bolivariana (UNEFA)</span>
      </div>
    </div>
    <button class="btn-logout">Cerrar Sesión</button>
  </div>
</header>

<!-- WELCOME BANNER -->
<div class="welcome-wrap">
  <div class="welcome-bar">
    <div class="welcome-bar-left">
      <h2>Bienvenido, <?= htmlspecialchars($estudiante['nombre_completo']) ?></h2>
      <p>Sistema de Registro de Postgrado &nbsp;·&nbsp; <?= htmlspecialchars($estudiante['fecha_sistema']) ?></p>
    </div>
    <div class="status-pill">
      <span class="status-dot"></span>
      En Línea
    </div>
  </div>
</div>
