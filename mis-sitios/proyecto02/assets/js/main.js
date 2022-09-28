$(document).ready(function() {

  function init() {
    //$('#do_get_game').modal('show');
  }
  init();

  // Funciones para mostrar notificaciones tipo toast
  // error | success | info | warning
  function toast(contenido , tipo = 'success', ) {
    switch (tipo) {
      case 'error' :
        toastr.error(contenido ,'¡Upss!');
        break;

      case 'info' :
        toastr.info(contenido,'Atención');
        break;

      case 'warning':
        toastr.warning(contenido,'Cuidado');
        break;
    
      default:
        toastr.success(contenido,'Bien hecho');
        break;
    }
    return true;
  }

  // ----------------------
  //
  // SESIONES DE USUARIO
  //
  // ----------------------

  // Registrar un nuevo usuario
  $('#do_register_user').on('submit' , do_register_user);
  function do_register_user(event) {
    event.preventDefault(); // Prevenir el submit regular
    var form = $(this),
    data = form.serialize(),
    action = 'register_user';

    // Validación antes de mandar la información
    if($('#user_name').val() == '') {
      toast('Ingresa tu nombre completo', 'error');
      return false;
    }

    if($('#user_email').val() == '') {
      toast('Ingresa tu correo electrónico', 'error');
      return false;
    }

    if($('#user_password').val() !== $('#user_password_conf').val()) {
      toast('Las contraseñas no coinciden','error');
      return false;
    }

    // Mandar la petición a ajax.php
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      data:
      {
        action,
        data
      },
      beforeSend: function() {
        $('body').waitMe();
      }
    }).done(function(res) {
      if(res.status === 201) {
        toast(res.msg);
        form.trigger('reset');
        setTimeout(() => {
          window.location = 'login.php';
        }, 2000);
      } else {
        toast(res.msg,'error');
      }
    }).fail(function(err) {

    }).always(function() {
      $('body').waitMe('hide');
    });

  }

  // Login de un usuario
  $('#do_login_user').on('submit' , do_login_user);
  function do_login_user(event) {
    event.preventDefault();

    var form = $(this),
    data = form.serialize(),
    action = 'login_user';

    // Hacemos la petición a ajax.php
    // Mandar la petición a ajax.php
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      data:
      {
        action,
        data
      },
      beforeSend: function () {
        $('body').waitMe();
      }
    }).done(function (res) {
      if (res.status === 200) {
        toast(res.msg);
        form.trigger('reset');
        setTimeout(() => {
          window.location = 'index.php';
        }, 2000);
      } else {
        toast(res.msg, 'error');
      }
    }).fail(function (err) {
      toast('Hubo un error en la petición','error');
      return;
    }).always(function () {
      $('body').waitMe('hide');
    });
  }

  // ----------------------
  //
  // VIDEOJUEGOS
  //
  // ----------------------

  // Procesar el formulario de agregar nuevo juego
  $('#do_add_game').on('submit' , do_add_game);
  function do_add_game(event) {
    event.preventDefault();

    // Validar el titulo
    if($('#titulo').val() == '' || $('#titulo').val().length < 5) {
      toast('Ingresa el título del juego','warning');
      return;
    }

    // Validar el genero del videojuego
    if($('#id_genero').val() == '') {
      toast('Selecciona un genero válido','warning');
      return;
    }

    // Validar la plataforma del videojuego
    if($('#id_plataforma').val() == '') {
      toast('Selecciona una plataforma válida','warning');
      return;
    }

    // Validar que la calificación no sea 0 ni mayor a 5
    if($('#calificacion').val() == 0 || $('#calificacion').val() > 5) {
      toast('Ingresa una calificación válida', 'warning');
      return;
    }

    // Validar la opinión del usuario
    if ($('#opinion').val() == '' || $('#opinion').val().length < 10) {
      toast('Ingresa una opinión válida para el videojuego, debe contener 10 o más caracteres', 'warning');
      return;
    }

    // Mandar la información a ajax
    var form = $(this),
    data = new FormData($('form').get(0)),
    action = 'add_game';

    // Agregar la acción al array de data
    data.append('action' , action);

    // Petición ajax
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      data: data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 201) {
        toast(res.msg);
        form.trigger('reset');
        setTimeout(() => {
          window.location = 'index.php';
        }, 1500);
      } else {
        toast(res.msg,'error');
        return false;
      }
    }).fail(function(err) {
      toast('Hubo un error, intenta de nuevo','error');
    }).always(function() {
      form.waitMe('hide');
    });
  }

  // Cargar un juego en ventana modal
  $('.do_view_game').on('click' , do_view_game);
  function do_view_game(e) {
    e.preventDefault();
    var boton = $(this),
    id = boton.data('id'),
    action = 'get_game';

    // Petición para cargar la información
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      data:
      {
        action,
        id
      },
      beforeSend: function() {
        $('body').waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        $('#single_game_modal').remove();
        $('body').append(res.data);
        $('#single_game_modal').modal('show');
      } else {
        toast(res.msg,'error');
      }
    }).fail(function(err) {
      toast('Hubo un error, intenta de nuevo', 'error');
    }).always(function(){
      $('body').waitMe('hide');
    })
  }

  // Actualizar un videojuego
  $('#do_update_game').on('submit' , do_update_game);
  function do_update_game(e) {
    e.preventDefault();

    // Validar el titulo
    if($('#titulo').val() == '' || $('#titulo').val().length < 5) {
      toast('Ingresa el título del juego','warning');
      return;
    }

    // Validar el genero del videojuego
    if($('#id_genero').val() == '') {
      toast('Selecciona un genero válido','warning');
      return;
    }

    // Validar la plataforma del videojuego
    if($('#id_plataforma').val() == '') {
      toast('Selecciona una plataforma válida','warning');
      return;
    }

    // Validar que la calificación no sea 0 ni mayor a 5
    if($('#calificacion').val() == 0 || $('#calificacion').val() > 5) {
      toast('Ingresa una calificación válida', 'warning');
      return;
    }

    // Validar la opinión del usuario
    if ($('#opinion').val() == '' || $('#opinion').val().length < 10) {
      toast('Ingresa una opinión válida para el videojuego, debe contener 10 o más caracteres', 'warning');
      return;
    }

    // Mandar la información a ajax
    var form = $(this),
    data = new FormData($('form').get(0)),
    submit = $('#submit'),
    action = 'update_game';

    // Agregar la acción al array de data
    data.append('action' , action);

    // Petición ajax
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      data: data,
      beforeSend: function() {
        //form.waitMe();
        submit.attr('disabled' , true);
      }
    }).done(function(res) {
      if(res.status === 200) {
        toast(res.msg);
        setTimeout(() => {
          window.location.reload();
        }, 1000);
        return true;        
      } else {
        toast(res.msg,'error');
        return false;
      }
    }).fail(function(err) {
      toast('Hubo un error, intenta de nuevo','error');
    }).always(function() {
      //form.waitMe('hide');
      setTimeout(() => {
        submit.attr('disabled' , false);
      }, 1500);
    });
  }

  // Borrando un videojuego del usuario
  $('body').on('click', '.do_delete_game' , do_delete_game);
  function do_delete_game(e) {
    e.preventDefault();

    var confirmation = confirm('¿Estás seguro?');

    if(!confirmation) return false;
    
    // Nuestras variables
    var boton = $(this),
    id = boton.data('id'),
    action = 'delete_game';

    // Petición a ajax.php
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      data:
      {
        action,
        id
      },
      beforeSend: function() {
        $('body').waitMe();
        boton.attr('disabled',true);
      }
    }).done(function(res) {
      if(res.status === 200) {
        toast(res.msg);
        setTimeout(() => {
          window.location.reload();
        }, 1500);
      } else {
        toast(res.msg , 'error');
        return false;
      }
    }).fail(function(err) {
      toast('Hubo un error, intenta de nuevo', 'error');
    }).always(function() {
      $('body').waitMe('hide');
      setTimeout(() => {
        boton.attr('disabled', false);
      }, 1500);
    });
  }

  // Cargar modal de "compartir"
  $('body').on('click', '.do_share_game', do_share_game);
  function do_share_game(e) {
    e.preventDefault();
    
    var boton = $(this),
    id = boton.data('id'),
    action = 'share_modal';

    // Petición ajax
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      data:
      {
        action,
        id
      },
      beforeSend: function() {
        //$(this).waitMe();
      }
    }).done(function(res){
      if(res.status === 200) {
        $('#share_game_modal').remove();
        $('body').append(res.data);
        $('#share_game_modal').modal('show');
      } else {
        toast(res.msg , 'error');
        return false;
      }
    }).fail(function(err){
      toast('Hubo un error, intenta de nuevo', 'error');
    }).always(function(){

    });
  }

  // Enviar mensaje de "compartir"
  $('body').on('submit','#do_submit_share_game',do_submit_share_game);
  function do_submit_share_game(e) {
    e.preventDefault();

    var form = $(this),
    data = form.serialize(),
    action = 'submit_share_game';

    // Validar que no esten vacios los campos
    if($('#mensaje').val().length < 5) {
      toast('Tu mensaje debe contener mínimo 5 caracteres','warning');
      return false;
    }

    // Petición ajax
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      data:
      {
        action,
        data
      },
      beforeSend: function() {
        $('body').waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        form.trigger('reset');
        toast(res.msg);
        return true;
      } else {
        toast(res.msg,'error');
        return false;
      }
    }).fail(function(err) {
      toast('Hubo un error, intenta de nuevo', 'error');
    }).always(function() {
      $('body').waitMe('hide');
    });
  }


});