<?php

namespace App\Controllers;

use App\Models\{User,Vehicle,Parking, Reservation};
use Respect\Validation\Validator as v;

class RegistryController extends BaseController
{
  public function startRegistryAction() {
    $welcome = 'Welcome to Marriot Hotel Reservation!';
    return $this->renderHTML('Registry.twig', [
      'responseMessage' => $welcome
    ]);
  }

  public function getAddRegistryAction($request)
  {
    $responseMessage = null;
    if ($request->getMethod() == 'POST') {
      $postData = $request->getParsedBody();
      $userValidator = v::key('nameuser', v::stringType()->notEmpty())->key('mailuser', v::email()); //miembros de un arreglo, attribute para un objeto

      try {
        $userValidator->assert($postData);
        $postData = $request->getParsedBody();

        //CREAMOS VARIABLES DE CONEXION BBS JAJAJAJAJAJ
        $user = new User();

        //INSERTAR A LA TABLA CLIENTE
        $name=$postData['nameuser'];
        $mail=$postData['mailuser'];
        $lasP=$postData['lastfather'];
        $lasM=$postData['lastmother'];
        $sexo=$postData['hm'];
        $age=$postData['age'];
        $user->nombre = $name;
        $user->correo = $mail;
        $user->apellidopaterno = $lasP;
        $user->apellidomaterno =$lasM;
        $user->sexo = $sexo;
        $user->edad= $age;
        $user->password = password_hash($postData['passworduser'], PASSWORD_DEFAULT);
        //FUNCION PARA GUARDAR TODOS LOS OBJETOS EN LA BD
        $user->save();
        $query = User::select('id_cliente')->where([['correo',$mail],['nombre',$name]])->first();
        $idCliente = $query->id_cliente;
        
        $veh = new Vehicle();
        $res = new Reservation();
        $veh->id_cliente = $idCliente;
        $res->id_cliente = $idCliente;
        $veh->save();
        $res->save();
        //MENSAJE HACIA EL REGISTRO.TWIG
        $responseMessage = 'Saved';
      } catch (\Exception $e) {
        $responseMessage = $e->getMessage();
      }
    }
    return $this->renderHTML('Registry.twig', [
      'responseMessage' => $responseMessage
    ]);
  }
}
