<?php

namespace App\Controllers;

use App\Models\{Reservation,Parking};

class SelectBoxController extends BaseController {
    public function postAddBox($request) {
        $session = $_SESSION['userId'];
        $str="ABDCDabcd1234567891011121314";
        $cad ='MARH';
      
        for($i=0;$i<8;$i++){
            $cad .= substr($str,rand(0,20),1);	
        }

        if ($request->getMethod() == 'POST') {
                  
    //Esta consulta genera un Array "piso","1"
    $querySelect = Reservation::select('id_reservacion')
    ->whereIn('piso', [1, 2, 3])
    ->where('id_cliente', $session)
    ->orderByDesc('created_at')->first();
    //Del Array generado le decimos que solo queremos el numero de piso e id_reservacion
    
    $id = $querySelect->id_reservacion; 
            
            $par = new Parking();
            //MANDAMOS EL FORREING KEY BEBAS!!!!
            $par->id_reservacion = $id;
            $par->id_cliente = $session;
            //GUARDAMOS
            $par->save();

            $posData = $request->getParsedBody();
            
            $numcajon= $posData['num'];
            $posicion= $posData['ubica'];
            $estado = 0;
            $tipo = $posData['type'];
            $precio= $posData['price'];
            $cantidad= $posData['count'];
            $vehicle= $posData['typecar'];


            $query = Parking::where([
                ['id_cliente', $session],
                ['id_reservacion', $id]])->update([
                'numcajon' => $numcajon,
                'posicion' => $posicion,
                'estado' => $estado,
                'tipo' => $tipo,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'vehiculo' => $vehicle,
                'codigo' => $cad
            ]);

            $queryCantidad = Parking::select('precio')->where('id_cliente', $session)->orderByDesc('created_at')->first();

        }
        return $this->renderHTML('Pay.twig', [
            'pay' => $queryCantidad
        ]);
    }
}