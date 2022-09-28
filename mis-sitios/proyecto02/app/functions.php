<?php

// ----------------------
//
// HELPERS
//
// ----------------------
function get_genders() {
  $stmt = 'SELECT * FROM generos ORDER BY id DESC';
  return ($rows = query_db($stmt)) ? $rows : false;
}

function get_platforms() {
  $stmt = 'SELECT * FROM plataformas ORDER BY id DESC';
  return ($rows = query_db($stmt)) ? $rows : false;
}

// render_view(carrito_view)
function render_view($view , $data = []) {
  if(!is_file(VIEWS.$view.'_view.php')) {
    //si no existe la vista, yo quiero que hagas esto:
    echo 'No existe la vista '.$view;
    die;
  }
  
  require_once VIEWS.$view.'_view.php';
}

function format_currency($number, $symbol = '$') {
  if(!is_float($number) && !is_integer($number)) {
    $number = 0;
  }

  return $symbol.number_format($number,2,'.',',');
}

function json_output($status = 200, $msg = '' , $data = []) {
  //http_response_code($status);
  $r =
  [
    'status' => $status,
    'msg'    => $msg,
    'data'   => $data
  ];
  echo json_encode($r);
  die;
}

function clean_string($string) {
  $string = trim($string);
  $string = rtrim($string);
  $string = ltrim($string);
  return $string;
}

function send_email($to , $subject = 'Nuevo mensaje' , $msg = NULL) {

  if(!filter_var($to , FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  if($msg == NULL) {
    $msg = "
    <html>
    <head>
    <title>HTML email</title>
    </head>
    <body>
    <p>This email contains HTML Tags!</p>
    <table>
    <tr>
    <th>Firstname</th>
    <th>Lastname</th>
    </tr>
    <tr>
    <td>John</td>
    <td>Doe</td>
    </tr>
    </table>
    </body>
    </html>
    ";
  }

  // Always set content-type when sending HTML email
  $headers  = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= 'From: '.COMPANY_NAME.' <'.COMPANY_EMAIL.'>' . "\r\n";
  // More headers

  mail($to,$subject,$msg,$headers);
  return true;
}

function get_date() {
  return date('Y-m-d H:i:s');
}

function redirect($url) {
  header('Location: '.URL.$url.'.php');
  die;
}

function generate_filename($lng = 8 , $span = 2) {
  if(!is_integer($lng)) {
    $lng = 8;
  }
  if(!is_integer($span)) {
    $span = 2;
  }
  $span = ($span > 5 ? 5 : $span);

  $filename = '';
  $min = '';
  $max = '';

  for ($i=0; $i < $lng; $i++) { 
    $min .= '1' ;
    $max .= '9' ;
  }

  for ($i=0; $i < $span; $i++) { 
    $filename .= rand((int) $min,(int) $max).'_';
  }

  return substr($filename,0,-1);
}

function get_image($path_to_image) {
  if(!is_file($path_to_image)) {
    return IMAGES.'broken-image.png';
  }
  return URL.$path_to_image;
}

function format_rating($rating) {
  $rating = (is_integer($rating) ? $rating : 3);

  $full = '<i class="fas fa-star"></i>';
  $empty = '<i class="far fa-star"></i>';
  $output = '';
  $min = 1;
  $max = 5;
  $msg =
  [
    'Decadente',
    'Malo',
    'Regular',
    'Muy bueno',
    'Excelente'
  ];

  $output .= '<div class="text-warning d-inline" data-toggle="tooltip" title="'.$msg[$rating - 1].'">';
  for ($i=0; $i < $max; $i++) { 
    if($rating > $i) {
      $output .= $full;
    } else {
      $output .= $empty;
    }
  }
  $output .= '</div>';

  return $output;
}

function format_date($date) {
  return date('d/m/Y' , strtotime($date));
}

// ----------------------
//
// FUNCIONES PARA INTERACTUAR CON LA DB
//
// ----------------------
function insert_new($table, $params = []) {
  // STATEMENT
  $stmt = 'INSERT INTO '.$table.' 
  '.get_column_names($params).'
  VALUES '.get_placeholders($params);

  // Ejecutamos el query y se inserta el registro
  return ($id = query_db($stmt , $params)) ? $id : false;
}

function update_record($table , $keys = [] , $params = []) {
  // UPDATE tabla SET columna=:placeholder, columna=:placeholder WHERE id=:id;
  $placeholders = '';
  $cols = '';

  foreach ($params as $k => $v) {
    $placeholders .= $k.'=:'.$k.',';
  }
  $placeholders = substr($placeholders, 0 , -1);
  
  $stmt = 'UPDATE '.$table.' SET '.$placeholders;

  // Si hay keys pues vamos a agregarlas al query o statement
  if(!empty($keys)) {
    $stmt .= ' WHERE ';
    foreach ($keys as $k => $v) {
      $cols .= $k.'=:'.$k.' AND';
    }
    $cols = substr($cols,0,-3);
    $stmt .= $cols;
  }

  // Ejecutar el statement o el query
  return (query_db($stmt , array_merge($keys , $params))) ? true : false;
}

function delete_record($table , $keys = []) {
  
  // Si hay keys pues vamos a agregarlas al query o statement
  if(empty($keys)) {
    return false;
  }

  $cols = '';
  $stmt = 'DELETE FROM '.$table;
  $stmt .= ' WHERE ';
  foreach ($keys as $k => $v) {
    $cols .= $k.'=:'.$k.' AND';
  }
  $cols = substr($cols,0,-3);
  $stmt .= $cols.' LIMIT 1';

  return (query_db($stmt , $keys , true)) ? true : false;
}

// USUARIOS VIDEOJUEGOS PLATAFORMAS GENEROS 50 100
// INSERT INTO tabla (COLUMNAS) VALUES (VALORES A INSERTAR);
function get_column_names($params) {
  // (nombre,email,password,navbar_color,creado)
  $cols = '';
  if(empty($params)) {
    return false;
  }

  $cols .= '(';
  foreach ($params as $k => $v) {
    $cols .= $k.',';
  }
  $cols = substr($cols,0,-1);
  $cols .= ')';

  return $cols;
}

function get_placeholders($params) {
  // (:nombre,:email,:password,:navbar_color,:creado)
  $placeholders = '';
  if(empty($params)) {
    return false;
  }

  $placeholders .= '(';
  foreach ($params as $k => $v) {
    $placeholders .= ':'.$k.',';
  }
  $placeholders = substr($placeholders,0,-1);
  $placeholders .= ')';

  return $placeholders;
}

// ----------------------
//
// FUNCIONES PARA SESIÓN DE USUARIO
//
// ----------------------

// Para crear la sesión de usuario
function init_user_session($inf_usuario) {
  // Cargar la información del usuario en login desde la base de datos
  // Nombre , email , navbar_color , creado
  // $_SESSION['current_user'];
  if(isset($_SESSION['current_user'])) {
    return false;
  }

  $usuario =
  [
    'id'           => (isset($inf_usuario['id']) ? $inf_usuario['id'] : NULL),
    'nombre'       => (isset($inf_usuario['nombre']) ? $inf_usuario['nombre'] : NULL),
    'email'        => (isset($inf_usuario['email']) ? $inf_usuario['email'] : NULL),
    'navbar_color' => (isset($inf_usuario['navbar_color']) ? $inf_usuario['navbar_color'] : NULL),
    'creado'       => (isset($inf_usuario['creado']) ? $inf_usuario['creado'] : NULL),
    'active'       => TRUE
  ];

  $_SESSION['current_user'] = $usuario;
  return true;
}

// Para verificar que la sesión está activa
function valid_session() {
  if(!isset($_SESSION['current_user'])) {
    return false;
  }

  if(!isset($_SESSION['current_user']['active'])) {
    return false;
  }

  $current_user = $_SESSION['current_user'];

  if($current_user['active'] !== TRUE) {
    return false;
  }

  return true;
}

// Para cargar la información del usuario
function cur_user() {
  if(!valid_session()) {
    return false;
  }

  return $_SESSION['current_user'];
}

// Para destruir la sesión
function destroy_user_session() {
  unset($_SESSION['current_user']);
  session_destroy();
  return true;
}

// ----------------------
//
// USUARIOS
//
// ----------------------

// get | insert | update | delete
function get_user_by_email($email) {
  $stmt = 'SELECT u.* FROM usuarios u WHERE u.email = :email LIMIT 1';

  return ($rows = query_db($stmt , ['email' => $email])) ? $rows[0] : false;
}

// ----------------------
//
// VIDEOJUEGOS
//
// ----------------------

// Cargar todos los juegos
function get_games() {

  // Para paginación de registros
  $stmt              = 'SELECT COUNT(v.id) AS total FROM videojuegos v';
  $total_records = query_db($stmt)[0]['total'];
  $offset            = NULL;
  $pagination        = NULL;
  $limit             = NULL;
  if($offset = create_offset($total_records)) {
    $limit = $offset[0];
    $pagination = $offset[1];
  }

  $stmt = 
  'SELECT 
  v.*,
  g.genero,
  p.plataforma,
  u.nombre,
  u.email
  FROM videojuegos v
  LEFT JOIN generos g ON v.id_genero = g.id
  LEFT JOIN plataformas p ON v.id_plataforma = p.id
  LEFT JOIN usuarios u ON v.id_usuario = u.id '.$limit;
  return ($rows = query_db($stmt)) ? [$rows,$pagination] : false;
}

function create_offset($registros_totales) {
  // Saber si está seteada la variable $_GET
  if($registros_totales == 0) {
    return false;
  }
  
  $offset = '';

  // Cantidad total de registros en la db
  $registros_totales = (int) $registros_totales;

  // Los registros que queremos mostrar por página
  $registros_pagina = RECORDS_PER_PAGE;

  // La cantida de páginas necesarias
  $paginas_totales = ceil($registros_totales/$registros_pagina);

  // Si get no es número lo pones como 1, y si get es mayor al total de páginas pues obviamente debería ser igual al total de páginas
  $pagina_actual = (isset($_GET['page']) ? $_GET['page']  : 1);
  
  if(!is_numeric($pagina_actual) || $pagina_actual < 1) {
    $pagina_actual = 1;
  }

  if($pagina_actual > $paginas_totales) {
    $pagina_actual = $paginas_totales;
  }
  
  $offset .= 'LIMIT '.($registros_pagina*($pagina_actual-1)).','.$registros_pagina;

  // Creamos la páginación o links de páginas
  $pagination = create_pagination($paginas_totales);

  return [$offset,$pagination];
}

function create_pagination($paginas_totales) {
  $paginas_totales = ($paginas_totales == 0 ? 1 : (int) $paginas_totales);
  // Si get no es número lo pones como 1, y si get es mayor al total de páginas pues obviamente debería ser igual al total de páginas
  $pagina_actual = (isset($_GET['page']) ? $_GET['page']  : 1);
  
  if(!is_numeric($pagina_actual) || $pagina_actual < 1) {
    $pagina_actual = 1;
  }

  if($pagina_actual > $paginas_totales) {
    $pagina_actual = $paginas_totales;
  }

  // HTML que representará nuestros links de navegación
  $links = '<ul class="pagination float-right">';
  $links .= 
  '<li class="page-item">
    <a class="page-link" href="'.basename($_SERVER['PHP_SELF']).'?page='.($pagina_actual == 1 ? 1 : $pagina_actual-1).'" aria-label="Anterior">
      <span aria-hidden="true">&laquo;</span>
    </a>
  </li>';
  // Loop entre todos los links
  for ($i = 1; $i <= $paginas_totales; $i++) {
    $links .= ($i != $pagina_actual ) 
    ? '<li class="page-item"><a class="page-link" href="'.basename($_SERVER['PHP_SELF']).'?page='.$i.'">'.$i.'</a></li>' 
    : '<li class="page-item active"><a class="page-link" href="'.basename($_SERVER['PHP_SELF']).'?page='.$pagina_actual.'" tabindex="-1" aria-disabled="true">'.$pagina_actual.'</a></li>';
  }
  $links .= 
  '<li class="page-item">
    <a class="page-link" href="'.basename($_SERVER['PHP_SELF']).'?page='.($pagina_actual == $paginas_totales ? $paginas_totales : $pagina_actual+1).'" aria-label="Siguiente">
      <span aria-hidden="true">&raquo;</span>
    </a>
  </li></ul>';

  return $links;
}

// Cargar todos los videojuegos de el usuario actual
function get_games_by_user($id_usuario) {
  // Para paginación de registros
  $stmt              = 'SELECT COUNT(v.id) AS total FROM videojuegos v WHERE v.id_usuario=:id_usuario';
  $total_records     = query_db($stmt , ['id_usuario' => $id_usuario])[0]['total'];
  $offset            = NULL;
  $pagination        = NULL;
  $limit             = NULL;
  if($offset = create_offset($total_records)) {
    $limit = $offset[0];
    $pagination = $offset[1];
  }

  // Quiero seleccionar TODAS las columnas de la tabla videojuegos del usuario que 
  // corresponda al $id_usuario pasado
  $stmt = 'SELECT v.* FROM videojuegos v WHERE v.id_usuario=:id_usuario ORDER BY v.id DESC '.$limit;

  return ($rows = query_db($stmt , ['id_usuario' => $id_usuario])) ? [$rows,$pagination] : false;
}

// Cargar un juego con id
function get_game_by_id($id) {
  $stmt = 
  'SELECT 
  v.*,
  g.genero,
  p.plataforma,
  u.nombre,
  u.email
  FROM videojuegos v
  JOIN generos g ON v.id_genero = g.id
  JOIN plataformas p ON v.id_plataforma = p.id
  JOIN usuarios u ON v.id_usuario = u.id
  WHERE v.id=:id 
  LIMIT 1';
  return ($rows = query_db($stmt , ['id' => $id])) ? $rows[0] : false;
}