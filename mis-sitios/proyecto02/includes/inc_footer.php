<!-- Footer -->
<footer id="footer" class="bg-light py-5">
  <div class="container">
    <div class="row">
      <div class="col-xl-4">
        <ul class="list-unstyled">
          <li><a href="#">Blog</a></li>
          <li><a href="#">RSS</a></li>
          <li><a href="#">Twitter</a></li>
          <li><a href="#">GitHub</a></li>
        </ul>
      </div>
      <div class="col-xl-4">
        <ul class="list-unstyled">
          <li><a href="index.php">Mis juegos</a></li>
          <li><a href="add.php">Agregar nuevo</a></li>
          <li><a href="all.php">Todos los juegos</a></li>
          <li><a href="#">Términos y condiciones</a></li>
        </ul>
      </div>
      <div class="col-xl-4 text-right">
        <p>Desarrollado por <a href="<?php echo URL; ?>"><?php echo COMPANY_NAME; ?></a>.</p>
      </div>
    </div>
  </div>
</footer>
<!-- END footer -->

<!-- jQuery de Bootstrap 4 -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>

<!-- Sweet alert 2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js"></script>

<!-- waitMe -->
<script src="assets/plugins/waitMe/waitMe.min.js"></script>

<!-- JS Cookie -->
<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>

<!-- Toastr -->
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
  // https://codeseven.github.io/toastr/demo.html
})
</script>

<!-- Main script -->
<script src="assets/js/main.js"></script>
</body>
</html>