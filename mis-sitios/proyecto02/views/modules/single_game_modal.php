<!-- Modal -->
<div class="modal fade" id="single_game_modal" tabindex="-1" role="dialog" aria-labelledby="ModalVideojuego" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $g['titulo']; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
          <img src="<?php echo get_image(UPLOADS.$g['portada']) ?>" alt="<?php echo $g['titulo']; ?>" class="img-fluid img-thumbnail">
        </div>
        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
          <h2 class="m-0"><?php echo $g['titulo'] ?> | <span class="text-muted"><?php echo $g['plataforma'] ?></span></h2>
          <p class="text-warning"><?php echo $g['genero'] ?></p>
          <?php echo format_rating((int) $g['calificacion']) ?>
          <p class="mt-3"><?php echo $g['opinion'] ?></p>
          <div class="btn-group" role="group">
            <a class="btn btn-sm btn-success" href="<?php echo 'update.php?id='.$g['id']; ?>" data-toggle="tooltip" title="Editar juego"><i class="fas fa-edit"></i></a>
            <button class="btn btn-sm btn-primary do_share_game" data-toggle="tooltip" title="Compartir juego" data-id="<?php echo $g['id']; ?>"><i class="fas fa-share"></i></button>
          </div>
          <button class="btn btn-sm btn-danger float-right do_delete_game" data-id="<?php echo $g['id']; ?>"><i class="fas fa-trash"></i> Borrar juego</button>
        </div>
       </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>