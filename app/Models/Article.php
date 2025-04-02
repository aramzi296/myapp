<?php

namespace App\Models;

use Spatie\Tags\HasTags;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media; // Tambahkan baris ini

class Article extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTags;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'author_id',
    ];


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            // Gunakan kolom 'title' untuk membuat slug
            ->generateSlugsFrom('title')
            // Simpan slug di kolom 'slug'
            ->saveSlugsTo('slug')
            // Opsi tambahan
            ->usingSeparator('-');
        // Mencegah perubahan slug setelah dibuat
        // ->preventOverwrite();
    }


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($article) {
    //         if (!$article->slug) {
    //             $slug = Str::slug($article->title);
    //             // Cek jika slug sudah ada
    //             $count = Article::where('slug', 'LIKE', "{$slug}%")->count();
    //             // Jika sudah ada, tambahkan angka di belakang
    //             $newSlug = $count ? "{$slug}-{$count}" : $slug;
    //             $article->slug = $newSlug;
    //         }
    //     });
    // }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function registerMediaConversions(Media $media = null): void // Pastikan namespace benar
    {
        $this->addMediaConversion('thumb')
            ->crop('crop-center', 300, 300);


        $this->addMediaConversion('medium')
            ->crop('crop-center', 600, 400);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('article')
            ->useDisk('cloudflare_r2');
    }

    public function isLikedByUser($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
}
