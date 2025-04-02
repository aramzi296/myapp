<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileUpload extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title'];

    // Jika single file upload, gunakan ini:
    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('cloudflare_r2')
    //         ->singleFile(); // Jika Anda ingin hanya satu file per koleksi
    // }


    // Jika multiple file upload, gunakan ini:
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cloudflare_r2')
            ->useDisk('cloudflare_r2'); // Use Cloudflare R2 disk
    }


    public function registerMediaConversions(Media $media = null): void
    {
        // Atur konversi media jika diperlukan
        // $this->addMediaConversion('thumb')
        //     ->width(250)
        //     ->height(250);
    }
}
