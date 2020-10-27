<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;

class AccountController extends BaseController{
  public function accountAction(){
    $session = $_SESSION['userId'];
    $qData = User::select('correo','nombre','apellidopaterno','apellidomaterno','sexo','edad','foto')->where('id_cliente', $session)->first();
    $foto = $qData->foto;

    if ($foto == 1 ) {
      $image = "img/usuario1.png";
    } else {
      $image = "img/usuario.png";
    }
    
    return $this->renderHTML('Account.twig', [
      'data' => $qData,
      'imagen' => $image
    ]);
  }

  public function postUpdatedProfile() {
    return $this->renderHTML('UpdateProfile.twig');
  }

  public function postNewProfile($request) {
    $session = $_SESSION['userId'];
    if ($request->getMethod() == 'POST') {
      $posData = $request->getParsedBody();
      $vehValidator = v::key('namepro', v::stringType()->notEmpty())->key('lastnamepro', v::stringType()->notEmpty())->key('lasnamemotherpro', v::stringType()->notEmpty())->key('mailpro', v::stringType()->notEmpty())->key('agepro', v::stringType()->notEmpty());
      //miembros de un arreglo, attribute para un objeto

      //Intanciamos un nuevo objeto del a tabla Vehiculo de la BD

        /*llamamos los datos del form
      y lo igualamos a fila de la bd
      para luego guardarlo en la BD*/
        $vehValidator->assert($posData);
        $posData = $request->getParsedBody();
        $name = $posData['namepro'];
        $lastP = $posData['lastnamepro'];
        $lastM = $posData['lasnamemotherpro'];
        $mail = $posData['mailpro'];
        $age = $posData['agepro'];
        $sexo = $posData['sexo'];

        $queryUser= User::select('id_cliente')->where('id_cliente', $session)->orderBYDesc('created_at')->first();
        $IdUser = $queryUser->id_cliente;

          $query = User::where(
            'id_cliente', $session)->update([
            'correo' => $mail,
            'nombre' => $name,
            'apellidopaterno' => $lastP,
            'apellidomaterno' => $lastM,
            'sexo' => $sexo,
            'edad' => $age]);

      $name = User::select('nombre','apellidopaterno')->where('id_cliente', $session)->first();
      $responseMessage = ', updated their data.';
    }
    return $this->renderHTML('Account.twig', [
      'name' => $name,
      'responseMessage' => $responseMessage
    ]);
  }
}
