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
  case 'get':
    $cart = get_cart();
    $output = '';
      if(!empty($cart['products'])) {
        $output .= '
        <div class="table-responsive">
          <table class="table table-hover table-striped table-sm">
            <thead>
              <tr>
                <th>Producto</th>
                <th class="text-center">Precio</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Total</th>
                <th class="text-right"></th>
              </tr>
            </thead>
            <tbody>';
            foreach ($cart['products'] as $p) {
              $output .= 
              '<tr>
                <td class="align-middle" width="25%">
                  <span class="d-block text-truncate">'.$p['nombre'].'</span>
                  <small class="d-block text-muted">SKU '.$p['sku'].'</small>
                </td>
                <td class="align-middle text-center">'.format_currency($p['precio']).'</td>
                <td class="align-middle text-center" width="5%">
                  <input data-id="'.$p['id'].'" data-cantidad="'.$p['cantidad'].'" type="text" class="form-control form-control-sm text-center do_update_cart" value="'.$p['cantidad'].'">
                </td>
                <td class="align-middle text-right">'.format_currency(floatval($p['cantidad'] * $p['precio'])).'</td>
                <td class="text-right align-middle">
                  <button class="btn btn-sm btn-danger do_delete_from_cart" data-id="'.$p['id'].'">
                  <i class="fas fa-times"></i>
                  </button>
                </td>
              </tr>';
            }
            $output .= '</tbody>
          </table>
        </div>
        <button class="btn btn-sm btn-danger do_destroy_cart">Vaciar carrito</button>';
      } else {
        $output .= '
        <div class="text-center py-5">
          <img src="'.IMAGES.'empty-cart.png'.'" title="No hay productos" class="img-fluid mb-3" style="width: 80px;">
          <p class="text-muted">No hay productos en el carrito</p>
        </div>';
      }
      $output .= 
      '<br><br>
      <!-- END Cart content -->
      
      <!-- Cart totals -->
      <table class="table">
        <tr>
          <th class="border-0">Subtotal</th>
          <td class="text-success text-right border-0">'.format_currency($cart['cart_totals']['subtotal']).'</td>
        </tr>
        <tr>
          <th>Envío</th>
          <td class="text-success text-right">'.format_currency($cart['cart_totals']['shipment']).'</td>
        </tr>
        <tr>
          <th>Total</th>
          <td class="text-success text-right"><h3 class="font-weight-bold">'.format_currency($cart['cart_totals']['total']).'</h3></td>
        </tr>
      </table>
      <!-- END Cart totals -->

      <!-- Payment form -->
      <form id="do_pay">
        <h4>Completa el formulario</h4>
        <div class="form-group">
          <label for="card_name">Nombre del titular</label>
          <input type="text" id="card_name" class="form-control" name="card_name" placeholder="John Doe">
        </div>
        <div class="form-group row">
          <div class="col-xl-6">
            <label for="card_number">Número en la tarjeta</label>
            <input type="text" id="card_number" class="form-control" name="card_number" placeholder="5755 6258 4875 6895">
          </div>
          <div class="col-xl-3">
            <label for="card_date">MM/AA</label>
            <input type="text" id="card_date" class="form-control" name="card_date" placeholder="12/24">
          </div>
          <div class="col-xl-3">
            <label for="card_cvc">CVC</label>
            <input type="text" id="card_cvc" class="form-control" name="card_cvc" placeholder="568">
          </div>
        </div>
        <div class="form-group">
          <label for="card_email">E-mail</label>
          <input type="email" id="card_email" class="form-control" name="card_email" placeholder="jslocal@localhost.com">
        </div>
        <button type="submit" class="mt-4 btn btn-info btn-lg btn-block"><b>Pagar ahora</b></button>
      </form>
      <!-- END Payment form -->';

    json_output(200, 'OK' , $output);
    break;

  // Agregar al carrito
  case 'post':
    if(!isset($_POST['id'],$_POST['cantidad'])) {
      json_output(403);
    }

    if(!add_to_cart((int)$_POST['id'] , (int)$_POST['cantidad'])) {
      json_output(400,'No se pudo agregar al carrito, intenta de nuevo');
    }

    json_output(201);
    break;
  
  case 'put':
    if(!isset($_POST['id'],$_POST['cantidad'])) {
      json_output(403);
    }

    if(!update_cart_product((int) $_POST['id'] , (int) $_POST['cantidad'])) {
      json_output(400,'No se pudo actualizar el producto, intenta de nuevo');
    }

    json_output(200);
    break;
  
  case 'destroy':
    if(!destroy_cart()) {
      json_output(400,'No se pudo destruir el carrito, intenta de nuevo');
    }

    json_output(200);
    break;

  case 'delete':
    if(!isset($_POST['id'])) {
      json_output(403);
    }

    if(!delete_from_cart((int)$_POST['id'])) {
      json_output(400,'No se pudo borrar el producto del carrito, intenta de nuevo');
    }

    json_output(200);
    break;
  
  case 'pay':
    // Verificar que haya un carrito existente
    $cart = get_cart();
    if(empty($cart['products'])) {
      json_output(400,'Tu carrito no tiene productos');
    }

    parse_str($_POST['data'],$_POST);
    if(!isset(
      $_POST['card_name'],
      $_POST['card_number'],
      $_POST['card_date'],
      $_POST['card_cvc'],
      $_POST['card_email']
    )) {
      json_output(400,'Completa todos los campos por favor e intenta de nuevo');
    }

    // Tarjeta falsa, debe coincidir la información que mande el usuario
    // con esta, para decir que es un pago aprobado
    $card =
    [
      'name'   => 'John Doe',
      'number' => '5755625848756895',
      'month'  => '12',
      'year'   => '24',
      'cvc'    => '568'
    ];

    // Validación del correo electrónico
    if(!filter_var($_POST['card_email'],FILTER_VALIDATE_EMAIL)) {
      json_output(400,'Ingresa una dirección de correo válida por favor e intenta de nuevo');
    }

    $errors = 0;
    // Validación de nombre
    if(clean_string($_POST['card_name']) !== $card['name']) {
      $errors++;
    }

    // Validación del número de tarjeta
    if(clean_string(str_replace(' ','',$_POST['card_number'])) !== $card['number']) {
      $errors++;
    }

    // Validación de la fecha
    // 12/24
    if(!empty($_POST['card_date'])) {
      $date = explode('/',$_POST['card_date']);
      if(count($date) < 2) {
        $errors++;
      }
      // array[12 , 24];
      if(clean_string($date[0]) !== $card['month']) {
        $errors++;
      }
      if(clean_string($date[1]) !== $card['year']) {
        $errors++;
      }
    } else {
      $errors++;
    }

    // Validación de el cvc
    if(clean_string($_POST['card_cvc']) !== $card['cvc']) {
      $errors++;
    }

    // Verificar si hay algún error
    if($errors > 0) {
      json_output(400,'Verifica la información de tu tarjeta por favor e intenta de nuevo');
    }

    // Guardamos el carro o la información del carro en otra variable para poder utilizarla como resumen
    // de compra
    // Número de compra
    $cart['order_number'] = rand(11111111,99999999);

    // Cliente de la compra
    $cart['client'] = $card;

    // Guardar resumen de compra
    $_SESSION['order_resume'] = $cart;

    destroy_cart();
    json_output(200);
    break;
  
  case 'order_resume':
    $c = get_order_resume();
    $output = 
    '<!-- Modal -->
    <div class="modal fade" id="order_resume" tabindex="-1" role="dialog" aria-labelledby="order_resume" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Resumen de compra</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="text-center py-4">
              <img src="'.IMAGES.'shopping-basket.png" alt="Resumen de compra" class="img-fluid" style="width: 100px;">
            </div>
            <h3>Gracias por tu compra</h3>
            <h5 class="my-0"><b>Número de compra #'.$c['order_number'].'</b></h5>
            Hemos recibido tu pago '.$c['client']['name'].', aquí tenemos el resumen de tu compra:<br><br>
            <table class="table table-hover table-striped table-sm">
            <thead>
              <tr>
                <th>Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Total</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($c['products'] as $p) {
              $output .= 
              '<tr>
                <td class="align-middle" width="25%">
                  <span class="d-block text-truncate">'.$p['nombre'].'</span>
                  <small class="d-block text-muted">SKU '.$p['sku'].'</small>
                </td>
                <td class="align-middle text-center" width="5%">'.$p['cantidad'].'</td>
                <td class="align-middle text-right">'.format_currency(floatval($p['cantidad'] * $p['precio'])).'</td>
              </tr>';
            }
            $output .= '
            <tr>
              <td class="align-middle text-left" colspan="2">Subtotal</td>
              <td class="align-middle text-right" colspan="1">'.format_currency($c['cart_totals']['subtotal']).'</td>
            </tr>
            <tr>
              <td class="align-middle text-left" colspan="2">Envío</td>
              <td class="align-middle text-right" colspan="1">'.format_currency($c['cart_totals']['shipment']).'</td>
            </tr>
            <tr>
              <td class="align-middle text-left" colspan="2">Total</td>
              <td class="align-middle text-right" colspan="1">'.format_currency($c['cart_totals']['total']).'</td>
            </tr>
            <tr>
              <td class="align-middle text-left" colspan="2">Forma de pago</td>
              <td class="align-middle text-right" colspan="1">Tarjeta terminación ***'.substr($c['client']['number'],-4).'</td>
            </tr>
            <tr>
              <td class="align-middle text-left" colspan="2">Estado del pago</td>
              <td class="align-middle text-right" colspan="1">Aprobado</td>
            </tr>
            </tbody>
          </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>';
    // Mensjae al usuario
    send_email('udemy@localhost.com','[Carritow] Tu resumen de compra',$output);

    // Mensaje a la empresa
    send_email('jslocal@localhost.com','[Carritow] ¡Recibimos una nueva venta!','<h1>Este es el resumen de compra del usuario</h1><br><br>'.$output);
    json_output(200,'',$output);
    break;
  
  default:
    json_output(403);
    break;
}

