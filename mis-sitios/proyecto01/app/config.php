<?php
/**
 * Inicialización de sesión de usuario
 */
session_start();

/**
 * URL constante
 */
define('PORT'     , '8889');
define('BASEPATH' , '');
define('URL'      , 'http://localhost:'.PORT.BASEPATH);

/**
 * Constantes para los paths de archivos
 */
define('DS'       , DIRECTORY_SEPARATOR);
define('ROOT'     , getcwd().DS);
define('APP'      , ROOT.'app'.DS);
define('INCLUDES' , ROOT.'includes'.DS);
define('VIEWS'    , ROOT.'views'.DS);

define('ASSETS'  , URL.'assets/');
define('CSS'     , ASSETS.'css/');
define('IMAGES'  , ASSETS.'images/');
define('JS'      , ASSETS.'js/');
define('PLUGINS' , ASSETS.'plugins/');

/**
 * Constantes adicionales
 */
define('SHIPPING_COST' , 99.90);
define('COMPANY_NAME'  , 'Carritow');
define('COMPANY_EMAIL' , 'noreplay@carritow.com');

/**
 * Incluir todas nuestras funciones personalizadas
 */
require_once APP.'functions.php';