<?php
//ESTE ES COMO SU SOBRE NOMBRE, COMO CUANDO BUSCAS UNA PALABRA EN EL DICCIONARIO Y EMPIEZA X "A" SOLO BUSCAS EN DONDE ESTA LAS "A", ASI ES ESTO, COMO LA RUTA A LA QUE SE REDIRIGE CUANDO LA LLAMAN
namespace App\Controllers;
//ESTOS SON LO MODELOS, OSEA LA INFORMACION DE LAS TABLAS DE LA BD
use App\Models\{Reservation, User, Vehicle};
//ESTO ES UNA LIBRERIA PARA QUE PUEDA DEVOLVER EL MENSAJE DE ERROR
use Zend\Diactoros\Response\RedirectResponse;

class IndexController extends BaseController
{
  //ESTA FUNCION ES PARA DIRECCIONAR AL HOME SIN ENVIAR DATOS
  public function indexAction()
  {
    //Capsula de una consulta a la BD
    $userCount = User::select('id_cliente')->count();
    //La salida de la consulta debe estar en forma de un arreglo
    return $this->renderHTML('Home.twig', [
      'userCount' => $userCount
    ]);
  }
  //ESTA FUNCION ES PARA CUANDO SE ENVIAN DATOS X POST
  public function postLogin($request)
  {
    //CREAMOS UNA VARIABLE CON LAS RESPUESTAS QUE VIENEN DESDE CUALQUIER FORMULARIO
    $postData = $request->getParsedBody();
    //EL MENSAJE ESTA EN NULO X ADELANTE SE LE DARA UN VALOR
    $responseMessage = null;
    //CONSULTA PARA TRAER EL CORREO DEL USUARIO QUE ESTA LOGEANDO
    $user = User::where('correo', $postData['correouser'])->first();
    if ($user) {
      //SI LA CONTRASEÑA ES IDENTICA A LA QUE ESTA EN AL BD
      if (password_verify($postData['passworduser'], $user->password)) {
        //ABRIMOS SESION CON EL ID_CLIENTE QUE TIENE LA TABLA CLIENTE
        $_SESSION['userId'] = $user->id_cliente;
        //INSTANCIAMOS ESE VALOR EN UNA VRIABLE PARA MEJOR MANEJO
        $session = $_SESSION['userId'];
        //INSTANCIAMOS UNA VARIABLE DE CONEXION PARA CADA TABLA
        $veh = new Vehicle();
        $res = new Reservation();
        //CONSULTA PARA TRAER LOS DATOS DE LA TABLA VEHICULO
        $queryV = Vehicle::select('marca','modelo','placa')->where('id_cliente', $session)->orderByDesc('created_at')->first();
        //CONSULTA PARA TRAER LOS DATOS DE LA TABLA RESERVACION
        $queryR = Reservation::select('piso')->where('id_cliente', $session)->orderByDesc('created_at')->first();
        //OBJETOS DE LA TABLA VEHICULO
        $marca = $queryV->marca;
        $modelo = $queryV->modelo;
        $placa = $queryV->placa;
        //OBJETOS DE LA TABLA RESERVACION
        $piso = $queryR->piso;
        //SI LOS DATOS DE LA TABLA VEHICULO NO ESTAN EN NULO
        if (!(($marca == null) && ($modelo == null) && ($placa == null))) {
          //AGREGAMOS OTRO ID_CLIENTE A LA TABLA VEHICULO
          $veh->id_cliente = $session;
          $veh->save();
        }
        //SI LOS DATOS DE LA TABLA RESERVACION NO ESTAN EN NULO
        if (!($piso == null)) {
          //AGREGAMOS OTRO ID_CLIENTE A LA TABLA RESERVACION
          $res->id_cliente = $session;
          $res->save();
        }
        //RETORNAMOS A LA VISTA
        return new RedirectResponse('/vehicle/add');
      } else {
        //MENSAJE EN CASO DE ERROR
        $responseMessage = 'Bad credentials';
      }
    } else {
      //MENSAJE EN CASO DE ERRROR
      $responseMessage = 'Bad credentials';
    } 
    //DATOS INCORRECTOS REGRESAMOS A INDEX
    return $this->renderHTML('Home.twig',
    //ESTO ENVIA DATOS DE REGRESO, YA SEA PARA MOSTRAR EN LA VISTA ,PARA ESO NECESITA COMO UN SOBRENOMBRE PARA PODERLO LLAMAR, NO SE PUEDE SOLO LA VARIABLE QUE EN ESTE CASO ES $responseMessage
    [
      'responseMessage' => $responseMessage
    ]);
  }
//FUNCION PARA ROMPER SESSION, SALIR DE LA CUENTA PUEJ
  public function getLogout() {
    //FUNCION PARA ROMPER SESSION
    unset($_SESSION['userId']);
    //UNA VEZ ROTA LA SESSION, LO MANDAMOS AL HOME
    return new RedirectResponse('/');
  }
}
