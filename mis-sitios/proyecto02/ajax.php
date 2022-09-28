<?php
require_once 'app/config.php';
// Función para sacar un json en pantalla
//echo json_encode($response);

// Qué tipo de petición está solicitando ajax
if(!isset($_POST['action'])) {
  json_output(403);
}

$action = $_POST['action'];

// GET
switch ($action) {
  case 'register_user' :
    if(!isset($_POST['data'])) {
      json_output(400);
    }

    // Pero si si está seteada
    parse_str($_POST['data'] , $data);

    // Validar el correo electrónico
    if(!filter_var($data['user_email'] , FILTER_VALIDATE_EMAIL)) {
      json_output(400,'La dirección de correo electrónico no es válida');
    }

    // Validar que el correo no exista ya
    if (get_user_by_email($data['user_email'])) {
      json_output(400,'La dirección de correo electrónico ya está registrada');
    }

    // Una segunda validación
    if(strlen($data['user_password']) < 5) {
      json_output(400,'Tu contraseña es demasiado corta, ingresa mínimo 5 caracteres');
    }

    if($data['user_password'] !== $data['user_password_conf']) {
      json_output(400,'Las contraseñas no coinciden');
    }

    // Guardar el usuario en la base de datos
    $usuario =
    [
      'nombre'   => clean_string($data['user_name']),
      'email'    => $data['user_email'],
      'password' => password_hash($data['user_password'].SALT,PASSWORD_DEFAULT),
      'creado'   => get_date()
    ];

    // Insertar el registro de usuario
    if(!insert_new('usuarios' , $usuario)) {
      json_output(400,'Hubo un problema, intenta de nuevo por favor');
    }

    json_output(201,'Te has registrado con éxito');
    break;
  
  case 'login_user' :
    if(!isset($_POST['data'])) {
      json_output(400);
    }

    // Pero si si está seteada
    parse_str($_POST['data'] , $data);

    // Validar el correo electrónico
    if(!filter_var($data['user_email'] , FILTER_VALIDATE_EMAIL)) {
      json_output(400,'La dirección de correo electrónico no es válida');
    }

    // Una segunda validación
    if(strlen($data['user_password']) < 5) {
      json_output(400,'Tu contraseña es demasiado corta, ingresa mínimo 5 caracteres');
    }

    // Información de usuario
    // Buscar en la db si existe el correo electrónico
    // si no existe pues no hay usuario, y no es válido
    $usuario = get_user_by_email($data['user_email']);
    if(!$usuario) {
      json_output(400,'La dirección de correo electrónico no existe');
    }

    // Si si existe, cargamos la información para validar su contraseña
    if (!password_verify($data['user_password'].SALT , $usuario['password'])) {
      json_output(400,'Las credenciales de ingreso no coinciden');
    }

    // Inicializar la sesión del usuario
    init_user_session($usuario);
    
    json_output(200,'Bienvenido de nuevo '.$usuario['nombre']);
    break;

  case 'add_game':
    if(!isset($_POST['titulo'],$_POST['id_genero'],$_POST['id_plataforma'],$_POST['calificacion'],$_POST['opinion'])) {
      json_output(400,'Completa el formulario por favor e intenta de nuevo');
    }

    // Crear nuestro array de información del nuevo juego
    $new_game =
    [
      'id_usuario'    => cur_user()['id'],
      'titulo'        => clean_string($_POST['titulo']),
      'id_genero'     => $_POST['id_genero'],
      'id_plataforma' => $_POST['id_plataforma'],
      'calificacion'  => $_POST['calificacion'],
      'opinion'       => clean_string($_POST['opinion']),
      'creado'        => get_date()
    ];

    // Si el usuario subió una imagen, procesarla
    if(isset($_FILES['portada']) && $_FILES['portada']['error'] !== 4) {
      // Primero vamos almacenarla en una variable
      $img = $_FILES['portada'];
      $ext = pathinfo($img['name'] , PATHINFO_EXTENSION);
      
      // Después vamos a renombrarla
      $new_name = generate_filename().'.'.$ext;

      // Después vamos a guardarla en nuestro SERVIDOR dentro de UPLOADS
      if(!move_uploaded_file($img['tmp_name'] , UPLOADS.$new_name)) {
        json_output(400,'Hubo un error al guardar la imagen, intenta de nuevo');
      }

      $new_game['portada'] = $new_name;
    }

    // Guardar en la base de datos
    if(!insert_new('videojuegos' , $new_game)) {
      json_output(400,'Hubo un problema, intenta de nuevo');
    }

    json_output(201,'Nuevo juego agregado con éxito');
    break;

  case 'get_game':
    if(!isset($_POST['id'])) {
      json_output(403,'Hubo un problema, intenta de nuevo');
    }

    // ID del juego que queremos ver
    $id = (int) $_POST['id'];

    // Cargar la información del juego
    $g = get_game_by_id($id);

    // Validar si existe o no el juego
    if(!$g) {
      json_output(400,'Juego no encontrado, intenta de nuevo');
    }

    // Cargar el html y formatearlo
    ob_start();
    require_once MODULES.'single_game_modal.php';
    $output = ob_get_clean();

    // Regresar el json con la información html
    json_output(200,'OK',$output);
    break;

  case 'update_game':
    if(!isset($_POST['id'],$_POST['titulo'],$_POST['id_genero'],$_POST['id_plataforma'],$_POST['calificacion'],$_POST['opinion'])) {
      json_output(403,'Completa el formulario por favor e intenta de nuevo');
    }

    // Crear nuestro array de información del nuevo juego
    $id = (int) $_POST['id'];
    $game =
    [
      'titulo'        => clean_string($_POST['titulo']),
      'id_genero'     => $_POST['id_genero'],
      'id_plataforma' => $_POST['id_plataforma'],
      'calificacion'  => $_POST['calificacion'],
      'opinion'       => clean_string($_POST['opinion'])
    ];

    // Si el usuario subió una imagen, procesarla
    if(isset($_FILES['portada']) && $_FILES['portada']['error'] !== 4) {
      // Obtener la imagen anterior si existe
      $portada_anterior = $_POST['portada_anterior'];

      // Primero vamos almacenarla en una variable
      $img = $_FILES['portada'];
      $ext = pathinfo($img['name'] , PATHINFO_EXTENSION);
      
      // Después vamos a renombrarla
      $new_name = generate_filename().'.'.$ext;

      // Después vamos a guardarla en nuestro SERVIDOR dentro de UPLOADS
      if(!move_uploaded_file($img['tmp_name'] , UPLOADS.$new_name)) {
        json_output(400,'Hubo un error al guardar la imagen, intenta de nuevo');
      }

      $game['portada'] = $new_name;
    }

    // Guardar en la base de datos
    if(!update_record('videojuegos', ['id' => $id] , $game)) {
      json_output(400,'Hubo un problema, intenta de nuevo');
    }

    // Antes de regresar la respuesta
    // Debemos borrar del servidor la imagen anterior
    if(isset($new_name) && is_file(UPLOADS.$new_name)) {
      if(is_file(UPLOADS.$portada_anterior)) unlink(UPLOADS.$portada_anterior);
    }

    json_output(200,'Cambios guardados con éxito');
    break;

  case 'delete_game':
    if(!isset($_POST['id'])) {
      json_output(403,'Acceso no autorizado');
    }

    $id = (int) $_POST['id'];

    // Validar que el juego es de hecho del usuario loggeado y que hace la petición
    if(!$game = get_game_by_id($id)) {
      json_output(400,'Juego no encontrado, intenta de nuevo');
    }

    // El usuario debe ser el mismo al id_usuario del registro
    if((int) $game['id_usuario'] !== (int) cur_user()['id']) {
      json_output(403);
    }

    // Borramos el registro
    if(!delete_record('videojuegos' , ['id' => $id])) {
      json_output(400,'Hubo un problema, intenta de nuevo');
    }

    // Borrar la imagen que sobra del registro
    if(is_file(UPLOADS.$game['portada'])) {
      unlink(UPLOADS.$game['portada']);
    }

    json_output(200,'Videojuego borrado con éxito u_u');
    break;
  
  case 'share_modal':
    if(!isset($_POST['id'])) {
      json_output(403);
    }

    // Cargar la información de el videojuego que pasamos con id
    $id = (int) $_POST['id'];

    // Cargar el juego
    if(!$g = get_game_by_id($id)) {
      json_output(400,'Juego no encontrado, intenta de nuevo');
    }

    // Cargar nuestro modulo
    ob_start();
    require_once MODULES.'share_game_modal.php';
    $output = ob_get_clean();

    // Regresar el json con la información html
    json_output(200,'OK',$output);
    break;
  
  case 'submit_share_game':
    if(!isset($_POST['data'])) {
      json_output(403);
    }

    parse_str($_POST['data'] , $data);

    if(!isset($data['id_videojuego'],$data['email'],$data['mensaje'])) {
      json_output(403);
    }

    // Validar el correo electrónico
    if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)) {
      json_output(400, 'El correo electrónico no es válido');
    }

    // Validar el mensaje
    if(strlen($data['mensaje']) < 5) {
      json_output(400, 'Tu mensaje es demasiado corto, debe contener 5 caracteres mínimo');
    }

    // Validar que siga existiendo el juego
    // Cargar la información del juego
    if(!$g = get_game_by_id($data['id_videojuego'])) {
      json_output(400,'Juego no encontrado, intenta de nuevo');
    }

    // Crear mensaje
    $output = 
    '<h3>'.$g['titulo'].'</h3>
    <p>'.clean_string($data['mensaje']).'</p>
    <img style="width: 200px;" src="'.get_image(UPLOADS.$g['portada']).'">
    <br><br>
    Este mensaje es generado de forma automática, favor de no responder.<br>
    <a href="'.URL.'register.php">Regístrate gratis</a> o <a href="'.URL.'login.php">Ingresa ahora</a>
    ';

    // Enviamos el mensaje al usuario
    if(!send_email($data['email'] , '['.COMPANY_NAME.'] Checa este juego - ¡Recomendado!' , $output)) {
      json_output(400,'El mensaje no pudo ser enviado, intenta de nuevo');
    }

    json_output(200,'Mensaje enviado con éxito');
    break;
  
  default:
    json_output(403);
    break;
}

