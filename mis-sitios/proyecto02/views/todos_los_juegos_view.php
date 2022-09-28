<?php require_once 'includes/inc_header.php' ?>
<?php require_once 'includes/inc_navbar.php' ?>

<!-- Content -->
<div class="container" style="padding: 150px 20px;">
  <div class="row">
    <!-- Game list -->
    <div class="col-xl-12">
      <h1 class="text-center mb-5"><?php echo $data['title']; ?></h1>
      <?php if ($data['games']): ?>
      <div class="row">
        <?php foreach ($data['games'] as $g): ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
          <div class="card">
            <img src="<?php echo get_image(UPLOADS.$g['portada']); ?>" alt="<?php echo $g['titulo']; ?>" class="card-img-top" style="height: 350px; object-fit: cover;">
            <div class="card-body p-2">
              <h5 class="card-title text-truncate mb-0"><?php echo $g['titulo']; ?></h5>
              <small class="d-block text-muted m-0"><?php echo 'Por '.$g['nombre'].' el '.format_date($g['creado']); ?></small>
              <?php echo format_rating((int) $g['calificacion']) ?>
              <?php if ($g['id_usuario'] == cur_user()['id']): ?>
                <a class="btn btn-sm btn-success float-right" href="<?php echo 'update.php?id='.$g['id']; ?>" data-toggle="tooltip" title="Editar juego"><i class="fas fa-edit"></i></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="row">
        <div class="col-xl-12 col-12">
          <?php if ($data['pagination']): ?>
          <?php echo $data['pagination']; ?>
          <?php endif; ?>
        </div>
      </div>
      <?php else: ?>
      <div class="text-center py-5">
        <img class="img-fluid" src="<?php echo IMAGES.'winner.png' ?>" alt="No hay videojuegos" style="width: 140px;">
        <p class="mt-3 text-muted">Lo sentimos, por el momento no hay videojuegos</p>
        <?php if (valid_session()): ?>
          <a href="add.php" class="mt-5 text-white btn btn-primary btn-lg">Agregar nuevo juego</a>
        <?php else: ?>
          <a href="register.php" class="mt-5 text-white btn btn-primary btn-lg">Regístrate gratis</a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      
    </div>
  </div>
</div>
<!-- END Content -->

<?php require_once 'includes/inc_footer.php' ?>