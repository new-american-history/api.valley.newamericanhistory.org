<?php

namespace Domain\Claims\Models;

use Illuminate\Database\Eloquent\Model;

class ChambersburgClaim extends Model
{
    protected $guarded = [];

    protected $dates = ['claim_date'];

    protected $casts = [
        'claim_number' => 'integer',
        'claim_total' => 'float',
        'personal_property' => 'float',
        'real_property' => 'float',
        'amount_awarded' => 'float',
        'amount_received' => 'float',
    ];
}
