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
              <h5 class="card-title text-truncate"><?php echo $g['titulo']; ?></h5>
              <?php echo format_rating((int) $g['calificacion']) ?>
              <button class="btn btn-sm btn-success float-right do_view_game" data-id="<?php echo $g['id']; ?>" data-toggle="tooltip" title="Ver juego"><i class="fas fa-eye"></i></button>
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
        <img class="img-fluid" src="<?php echo IMAGES.'game-controller.png' ?>" alt="No hay videojuegos" style="width: 100px;">
        <p class="mt-3 text-muted">No tienes videojuegos favoritos, intenta agregando el primero</p>
        <button class="mt-5 btn btn-primary btn-lg">Agregar nuevo juego</button>
      </div>
      <?php endif; ?>
      
    </div>
  </div>
</div>
<!-- END Content -->

<?php require_once 'includes/inc_footer.php' ?>