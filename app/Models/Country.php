<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function member()
    {
        return $this->hasOne(Member::class);
    }
}
