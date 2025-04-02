<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'prefix',
        'name',
        'suffix',
        'user_id',
        'job_title',
        'company',
        'department',
        'address',
        'country_id',
        'website',
        'bio',
        'title',
        'slug',
        'phone_number',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
