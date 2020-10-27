<?php

namespace App\Controllers;

use App\Models\{Vehicle,User};
//LIBRERIA PARA VALIDAR LOS DATOS, COMO X EJ. Q NO VENGAN VACIDOS DE LOS FORMULARIOS ETC.
use Respect\Validation\Validator as v;
//ESTO ES HERENCIA, PARTIMOS DE LA CLASE PADRE QUE EN ESTE CASO ES BASECONTROLLER
class VehicleController extends BaseController
{
  //LAS FUNCIONES CORTAS LO QUE HACEN ES REDIRIGIR Y LAS MAS LARGAR CON QUERYS Y DATOS SON LAS QUE MANDAN LOS FORMULARIOS
  public function startVehicleAction() {
    $session = $_SESSION['userId'];
    $vehCount = Vehicle::select('id_vehiculo')->where('id_cliente', $session)->count();
    $name = User::select('nombre')->where('id_cliente',$session)->first();
    $vehTotal = $vehCount-1;
    $message = ', currently has '.$vehTotal.' vehicle(s) in the System';
    return $this->renderHTML('AddVehicle.twig', [
      'name' => $name,
      'responseMessage' => $message
    ]);
  }

  public function getAddVehicleAction($request)
  {
    $responseMessage = null;
    //Si el formulario no esta vacio
    if ($request->getMethod() == 'POST') {
      $posData = $request->getParsedBody();
      $vehValidator = v::key('modelcar', v::stringType()->notEmpty())->key('brandcar', v::stringType()->notEmpty())->key('platecar', v::stringType()->notEmpty())->key('ownercar', v::stringType()->notEmpty());
      //miembros de un arreglo, attribute para un objeto

      //Intanciamos un nuevo objeto del a tabla Vehiculo de la BD

      try {
        $vehValidator->assert($posData);
        $posData = $request->getParsedBody();
        /*llamamos los datos del form
      y lo igualamos a fila de la bd
      para luego guardarlo en la BD*/
        $session = $_SESSION['userId'];
        $modelo = $posData['modelcar'];
        $marca = $posData['brandcar'];
        $color = $posData['colorcar'];
        $tipo = $posData['typecar'];
        $placa = $posData['platecar'];
        $seguro = $posData['numbercar'];
        $propiedad = $posData['ownercar'];
        $descripcion = $posData['descriptioncar'];
        //QUERY PARA SABER EL ID_ESTACIONAMIENTO MAS RECIENTE
        $queryVehicle= Vehicle::select('id_vehiculo')->where('id_cliente', $session)->orderBYDesc('created_at')->first();
        //TRAEMOS LO DEL ID X Q VIENE EN ARRAY
        $IdVehicle = $queryVehicle->id_vehiculo;

        //Mandamos los datos a la BD
        $query = Vehicle::where([
          ['id_cliente', $session],
          ['id_vehiculo', $IdVehicle]])->update([
          'modelo' => $modelo,
          'marca' => $marca,
          'color' => $color,
          'tipo' => $tipo,
          'placa' => $placa,
          'seguro' => $seguro,
          'propiedad' => $propiedad,
          'descripcion' => $descripcion
        ]);
        //ENVIAMOS UN MENSAJE DE CONFIRMACION AL LA VISTA DE ADD-VEHICLE
        $veh = new Vehicle();
        $veh->id_cliente = $session;
        $veh->save();
        $responseMessage = 'Vahicle Saved!!!';
      } catch (\Exception $e) {
        //MANDAMOS EL ERROR QUE SE PRODUJO X LA CONEXION
        $responseMessage = $e->getMessage();
      }
    }
    return $this->renderHTML('AddVehicle.twig', [
      'responseMessage' => $responseMessage
    ]);
  }
}
