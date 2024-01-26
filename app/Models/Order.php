<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model{
//============================Start=============================================//
    use HasFactory;
    protected $table='orders';
    protected $fillable=['id','user_id','amount'];

//============================/End=============================================//
}
