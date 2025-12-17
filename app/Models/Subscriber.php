<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'sim',
        'zip',
        'plan_soc',
        'imei',
        'label',
        'e911_address_street1',
        'e911_address_street2',
        'e911_address_city',
        'e911_address_state',
        'e911_address_zip',
        'transaction_id',
        'msisdn',
        'iccid',
        'account_id',
        'api_status',
    ];
}
