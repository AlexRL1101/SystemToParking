<?php
//Es como asignar que busque en esta ruta con este nombre
namespace App\Models;
//Establecer que utilizaremos el motor de BD de Illuminate/Databases
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
    protected $table = 'reservacion';
}