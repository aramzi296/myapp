<?php

namespace App\Models;

use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Model;

class Gfile extends Model
{
    use HasTags;

    protected $fillable = [
        'description',
    ];

    public function medias()
    {
        return $this->hasMany(GfileMedia::class, 'gfile_id');
    }
}
