<?php

namespace App\Controllers;

use App\Models\Reservation;

class HistoryController extends BaseController{
  public function historyAction(){
    $session = $_SESSION['userId'];

    $queryFech = Reservation::select('reservacion.fechareservar','reservacion.horareservar','reservacion.piso','estacionamiento.vehiculo','estacionamiento.codigo')
    ->join('estacionamiento', 'estacionamiento.id_reservacion','=','estacionamiento.id_reservacion')
    ->whereIn('reservacion.piso',[1,2,3])
    ->whereNotNull('estacionamiento.tipo')
    -> whereColumn ( 'reservacion.id_reservacion' ,  '=' ,  'estacionamiento.id_reservacion' ) 
    ->where([['reservacion.id_cliente', $session],['estacionamiento.id_cliente', $session],['estacionamiento.numcajon','!=',0]])->get();

    /*echo $queryFech;
     $leagues = League::select('league_name')
    ->join('countries', 'countries.country_id', '=', 'leagues.country_id')
    ->where('countries.country_name', $country)
    ->get(); */
    
    return $this->renderHTML('History.twig', [
      'date' => $queryFech
    ]);
  }
}
