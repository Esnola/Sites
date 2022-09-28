<?php 
// PHP Y SUS FUNCIONES PREDEFINIDAS ESTÁN TODAS ATRAS DE ESTO
require_once 'app/config.php';

destroy_user_session();

header('Location: '.URL.'login.php');
die;