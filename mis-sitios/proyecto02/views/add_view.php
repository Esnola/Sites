<?php require_once 'includes/inc_header.php' ?>
<?php require_once 'includes/inc_navbar.php' ?>

<!-- Content -->
<div class="container" style="padding: 150px 0px;">
  <div class="row">
    <div class="offset-xl-3 col-xl-6">
      <div class="card">
        <div class="card-body">
          <h2 class="text-center mb-5"><?php echo $data['title']; ?></h2>
          <!-- 
            // id
            // id_usuario x
            // portada
            // titulo
            // id_genero
            // id_consola
            // calificacion
            // opinion
            // creado x
            // actualizado x
           -->
          <form id="do_add_game">
            <div class="form-group">
              <label for="portada">Portada del juego</label>
              <input type="file" class="form-control-file" id="portada" name="portada" accept="image/*">
            </div>
            <div class="form-group">
              <label for="titulo">Título</label>
              <input type="text" class="form-control" id="titulo" name="titulo">
            </div>
            <div class="form-group">
              <label for="id_genero">Genero</label>
              <select class="form-control" id="id_genero" name="id_genero">
                <option value="">Seleciona una opción...</option>
                <?php foreach (get_genders() as $p): ?>
                  <option value="<?php echo $p['id'] ?>"><?php echo $p['genero'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="id_plataforma">Plataforma</label>
              <select class="form-control" id="id_plataforma" name="id_plataforma">
                <option value="">Seleciona una opción...</option>
                <?php foreach (get_platforms() as $p): ?>
                  <option value="<?php echo $p['id'] ?>"><?php echo $p['plataforma'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="calificacion">Calificación</label>
              <input type="range" class="form-control" min="1" max="5" value="1" id="calificacion" name="calificacion">
            </div>
            <div class="form-group">
              <label for="opinion">Opinión</label>
              <textarea name="opinion" id="opinion" cols="30" rows="10" class="form-control">Hola mundo de nuevo!</textarea>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success">Agregar</button>
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