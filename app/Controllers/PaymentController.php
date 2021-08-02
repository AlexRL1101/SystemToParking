<?php

namespace App\Controllers;

use App\Models\{Parking, Pay, Reservation,Transaction};
use Respect\Validation\Validator as v;

class PaymentController extends BaseController {
    public function postChargePayment($request)
    {

        $session = $_SESSION['userId'];
        if ($request->getMethod() == 'POST') {
            $resValidator = v::key('first_name', v::stringType()->notEmpty())->key('last_name', v::stringType()->notEmpty())->key('email', v::stringType()->notEmpty());

            $postData = $request->getParsedBody();
            $resValidator->assert($postData);

            $first_name = $postData['first_name'];
            $last_name = $postData['last_name'];
            $email = $postData['email'];
            $token = $postData['stripeToken'];

            // Create Customer In Stripe
$customer = \Stripe\Customer::create(array(
    "email" => $email,
    "source" => $token
  ));
  
  $queryCount = Parking::select('precio')->where('id_cliente',$session)->orderByDesc('created_at')->first();
  $count= $queryCount->precio;

  // Charge Customer
  $charge = \Stripe\Charge::create(array(
    "amount" => $count,
    "currency" => "mx",
    "description" => "Reservation of Hotel Marriot",
    "customer" => $customer->id
  ));
  
  $idReservation = Reservation::select('id_reservacion','id_pago')->where('id_cliente',$session)->orderByDesc('created_at')->first();
  $var1=$idReservation->id_reservacion;
  $var2=$idReservation->id_pago;

  $queryPago = Pay::where('id_reservacion',$var1)
  ->update([
    'id' => $charge->customer,
    'nombre' => $first_name,
    'apellido' => $last_name,
    'email' => $email
  ]);
  
$pa = new Transaction();
$pa->id_pago = $var2;
$pa->save();
  
  $queryPago = Pay::where('id_pago',$var1)
  ->update([
    'id' => $charge->id,
    'customer_id' => $charge->customer,
    'product' => $charge->description,
    'amount' => $charge->amount,
    'currency' => $charge->currency,
    'status' => $charge->status,
  ]);

  $queryPago = 'Successful payment, in this section is your access codes';

        }
        return $this->renderHTML('History.twig', [
            'success' => $queryPago
        ]);
    }
}