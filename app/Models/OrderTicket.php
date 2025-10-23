<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTicket extends Model
{
    protected $fillable =['type', 'name', 'message', 'phone'];
}
