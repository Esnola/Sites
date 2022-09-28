<?php require_once 'includes/inc_header.php' ?>
<?php require_once 'includes/inc_navbar.php' ?>

<!-- Content -->
<div class="container" style="padding: 150px 0px;">
  <div class="row">
    <div class="offset-xl-3 col-xl-6">
      <div class="card">
        <div class="card-body">
          <h2 class="text-center mb-5"><?php echo $data['title']; ?></h2>
          <form id="do_update_game">
            <input type="hidden" name="id" value="<?php echo $data['g']['id']; ?>">
            <input type="hidden" name="portada_anterior" value="<?php echo $data['g']['portada']; ?>">
            <div class="form-group">
              <img src="<?php echo get_image(UPLOADS.$data['g']['portada']); ?>" alt="<?php echo $data['g']['titulo'] ?>" class="img-fluid img-thumbnail" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;" data-toggle="tooltip" title="<?php echo 'Portada actual: '.$data['g']['titulo']; ?>">
            </div>
            <div class="form-group">
              <label for="portada">Portada del juego</label>
              <input type="file" class="form-control-file" id="portada" name="portada" accept="image/*">
            </div>
            <div class="form-group">
              <label for="titulo">Título</label>
              <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $data['g']['titulo'] ?>">
            </div>
            <div class="form-group">
              <label for="id_genero">Genero</label>
              <select type="email" class="form-control" id="id_genero" name="id_genero">
                <option value="">Seleciona una opción...</option>
                <?php foreach (get_genders() as $p): ?>
                  <?php if ($p['id'] == $data['g']['id_genero']): ?>
                    <option value="<?php echo $p['id'] ?>" selected><?php echo $p['genero'] ?></option>
                  <?php else: ?>
                    <option value="<?php echo $p['id'] ?>"><?php echo $p['genero'] ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="id_plataforma">Plataforma</label>
              <select type="email" class="form-control" id="id_plataforma" name="id_plataforma">
                <option value="">Seleciona una opción...</option>
                <?php foreach (get_platforms() as $p): ?>
                  <?php if ($p['id'] == $data['g']['id_plataforma']): ?>
                    <option value="<?php echo $p['id'] ?>" selected><?php echo $p['plataforma'] ?></option>
                  <?php else: ?>
                    <option value="<?php echo $p['id'] ?>"><?php echo $p['plataforma'] ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="calificacion">Calificación</label>
              <input type="range" class="form-control" min="1" max="5" id="calificacion" name="calificacion" value="<?php echo $data['g']['calificacion'] ?>">
            </div>
            <div class="form-group">
              <label for="opinion">Opinión</label>
              <textarea name="opinion" id="opinion" cols="30" rows="10" class="form-control"><?php echo $data['g']['opinion'] ?></textarea>
            </div>
            <div class="form-group">
              <button id="submit" type="submit" class="btn btn-success">Guardar cambios</button>
              <button type="reset" class="btn btn-default">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END Content -->

<?php require_once 'includes/inc_footer.php' ?>