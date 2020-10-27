<?php

namespace App\Controllers;

use App\Models\{Parking, Reservation, User, Time, Pay, Vehicle};
use Respect\Validation\Validator as v;
use Illuminate\Database\Capsule\Manager as Capsule;

class ReservationController extends BaseController
{
  public function startReservation()
  {
    $userCount = User::select('id_cliente')->count();
    return $this->renderHTML('Reservation.twig', [
      'countUser' => $userCount
    ]);
  }

  public function postAddReservationAction($request)
  {
    $session = $_SESSION['userId'];
    if ($request->getMethod() == 'POST') {
      $postData = $request->getParsedBody();
      $resValidator = v::key('nivelreser', v::stringType()->notEmpty());

      $resValidator->assert($postData);
      $fechareservar = $postData['fechareser'];
      $horareservar = $postData['tiemporeser'];
      $horasalida = $postData['tiemposalida'];
      $fechasalida = $postData['fechasalida'];
      $piso = $postData['nivelreser'];
      
      $queryReservation = Reservation::select('id_reservacion')->where('id_cliente', $session)->orderBYDesc('created_at')->first();
      $IdReserva = $queryReservation->id_reservacion;

      $query = Reservation::where([['id_cliente', $session],['id_reservacion', $IdReserva]])->update([
        'piso' => $piso,
        'fechareservar' => $fechareservar,
        'horareservar' => $horareservar,
        'horasalida' => $horasalida,
        'fechasalida' => $fechasalida
      ]);

      $floorRute= '';
      if ($piso == 1) {
        $floorRute = 'First.twig';
      } else {
        if ($piso == 2) {
          $floorRute = 'Second.twig';
        } else {
          $floorRute = 'Third.twig';
        }
      }
      
    //Esta consulta genera un Array "piso","1"
    $querySelect = Reservation::select('id_reservacion')->where('id_cliente', $session)->orderByDesc('created_at')->first();
    //Del Array generado le decimos que solo queremos el numero de piso e id_reservacion
    
    $id = $querySelect->id_reservacion; 
    
    //INSERTAMOS EN LA FOREIGN KEY id_cliente
    $res = new Reservation();
    $res->id_cliente = $session;
    $res->save();

      $pay = new Pay();
    //MANDAMOS EL FORREING KEY BEBAS!!!!
      $pay->id_reservacion = $id;
      //GUARDAMOS
      $pay->save();

      $cantidad = Vehicle::all()->where('id_cliente',$session);
    //Redirigimos a la ruta que toca

      $queryEnableFech = Capsule::table('estacionamiento')
          ->select('posicion')
          ->join('reservacion','reservacion.id_reservacion','=','estacionamiento.id_reservacion')
          ->where('reservacion.piso',$piso)
          ->whereBetween('reservacion.fechareservar', [$fechareservar,$fechasalida])
          ->whereBetween('reservacion.horareservar', [$horareservar,$horasalida])
          ->whereBetween('reservacion.fechasalida', [$fechareservar,$fechasalida])            ->whereBetween('reservacion.horasalida', [$horareservar,$horasalida])
          ->get();

    return $this->renderHTML($floorRute, [
      'jobs' => $cantidad,
      'ocup' => $queryEnableFech
    ]);
  }
 }
}