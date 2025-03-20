<?php

namespace App\Models;

use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class FileEntry extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasTags;


    protected $fillable = [
        'description',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('ramzi')
            // ->useDisk('google');
            ->useDisk('r2');
    }
}
