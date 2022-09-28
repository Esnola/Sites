<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-warning">
  <div class="container">
    <a class="navbar-brand" href="<?php echo URL; ?>"><?php echo COMPANY_NAME; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor02">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item <?php echo (isset($data['active']) && $data['active'] == 'index' ? 'active' : '') ?>">
          <a class="nav-link" href="index.php">Mis juegos</a>
        </li>
        <li class="nav-item <?php echo (isset($data['active']) && $data['active'] == 'add' ? 'active' : '') ?>">
          <a class="nav-link" href="add.php">Agregar nuevo juego</a>
        </li>
        <li class="nav-item <?php echo (isset($data['active']) && $data['active'] == 'all' ? 'active' : '') ?>">
          <a class="nav-link" href="all.php">Todos los juegos</a>
        </li>
      </ul>
      <ul class="navbar-nav">
        <?php if (valid_session()): ?>
          <li class="nav-item">
            <a class="nav-link" href="#"><?php echo cur_user()['nombre'] ?></a>
          </li>
          <li class="nav-item <?php echo (isset($data['active']) && $data['active'] == 'logout' ? 'active' : '') ?>">
            <a class="nav-link" href="logout.php">Cerrar sesión</a>
          </li>
        <?php else: ?>
          <li class="nav-item <?php echo (isset($data['active']) && $data['active'] == 'register' ? 'active' : '') ?>">
            <a class="nav-link" href="register.php">Registrarse</a>
          </li>
          <li class="nav-item <?php echo (isset($data['active']) && $data['active'] == 'login' ? 'active' : '') ?>">
            <a class="nav-link" href="login.php">Ingresar</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<!-- END Navbar -->