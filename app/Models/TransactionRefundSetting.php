<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionRefundSetting extends Model
{
    

    protected $table = 'transaction_refund_settings'; 
    protected $fillable = [ 'return_time', 'partner' ];
}
