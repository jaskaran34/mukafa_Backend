<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otp';


    protected $fillable = [
        'unique_id',
        'otp',
        'request_type',
        'mukafa_no',
        'remarks',
        'expires_at',
    ];

}
