<?php 
// PHP Y SUS FUNCIONES PREDEFINIDAS ESTÁN TODAS ATRAS DE ESTO
require_once 'app/config.php';

// Validar la sesión de usuario
if(!valid_session()) {
  redirect('register');
}

$data =
[
  'title' => 'Agregar nuevo juego',
  'active' => 'add'
];

// id
// id_usuario xxx
// portada
// titulo
// id_genero
// id_consola
// calificacion
// opinion
// creado
// actualizado

// Renderizado de la vista
render_view('add' , $data);
