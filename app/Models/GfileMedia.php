<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GfileMedia extends Model
{
    protected $fillable = [
        'gfile_id',
        'driver',
        'path',
        'file_name',
        'file_extension',
        'file_size',
        'file_mime_type',
    ];

    public function gfile()
    {
        return $this->belongsTo(Gfile::class, 'gfile_id');
    }
}
