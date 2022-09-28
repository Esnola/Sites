<?php 

// Realizar una conexión a la base de datos
function make_con(
$db_engine  = DB_ENGINE , 
$db_host    = DB_HOST , 
$db_name    = DB_NAME , 
$db_user    = DB_USER , 
$db_pass    = DB_PASS , 
$db_charset = DB_CHARSET) {
  try {
    $connection = new PDO($db_engine.':host='.$db_host.';dbname='.$db_name.';charset='.$db_charset , $db_user , $db_pass);
    //echo 'Conectado con éxito';
    return $connection;
  } catch (PDOException $e) {
    die('No hay conexión con la base de datos<br><span style="color: red;"> '.$e->getMessage().'</span>');
  }
}

// Petición a la base de datos
// SELECT * FROM usuarios;
// INSERT -> $ID
// UPDATE -> 
// DELETE -> 
function query_db($stmt, $params = [] , $debug = false) {
  $con = make_con();

  // Necesitamos preparar nuestro enunciado o consulta
  $query = $con->prepare($stmt);

  // Vamos a ejecutar la información dentro de query ($stmt)
  // INSERT INTO usuarios (nombre , email) VALUES (:nombre , :email)
  if(!$query->execute($params)) {
    // TODO SI SALE MAL
    // NO PUDO INSERTARSE
    // NO PUDO BORRARSE
    // NO PUDO ACTUALIZARSE
    // NO PUDO EJECUTARSE LA SELECCIÓN
    if($debug) {
      $error = $query->errorInfo();
      echo $error[0].'<br>';
      echo $error[1].'<br>';
      echo $error[2];
    }

    return false;
  }

  // TODO SI SALE BIEN
  // HAY O NO HAY RESULTADOS
  // SE INSERTO EL REGISTRO
  // SE ACTUALIZO 0 O MÁS COLUMNAS
  // SE BORRO ÉXITO 0 MÁS COLUMNAS
  // CRUD
  $count = 0;
  $count = $query->rowCount();

  if(strpos($stmt , 'SELECT') !== false) {
    // Selección o busqueda de información
    // Necesitamos contar los resultados encontrados y regresarlos
    if($count > 0) {
      return $query->fetchAll();
    }
    return false;

  } elseif(strpos($stmt , 'INSERT INTO') !== false) {
    // Necesitamos regresar el id de la fila insertada
    if($count > 0) {
      return $con->lastInsertId();
    }
    return false;

  } elseif(strpos($stmt , 'UPDATE') !== false) {
    // Necesitamos contar cuantos registros se actualizaron y si son 0 o más
    // regresamos true
    if($count >= 0) {
      return true;
    }

  } elseif(strpos($stmt , 'DELETE') !== false) {
    // Regresar true si son 0 o más filas afectadas
    if($count > 0) {
      return true;
    }
    return false;

  }
  return true;
}