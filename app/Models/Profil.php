<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Profil extends Model
{
    use HasFactory, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'gelar_awalan',
        'nama_lengkap',
        'gelar_akhiran',
        'slug',
        'nomor_ktp',
        'alamat',
        'kelurahan_id',
        'kecamatan_id',
        'kota_id',
        'propinsi_id',
        'negara_id',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_perkawinan',
        'pendidikan_terakhir',
        'pas_foto',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['nama_lengkap'])
            ->saveSlugsTo('slug')
            ->usingSeparator('-')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relationship with User model
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor for full name with titles
     *
     * @return string
     */
    public function getNamaLengkapWithGelarAttribute(): string
    {
        return trim("{$this->gelar_awalan} {$this->nama_lengkap} {$this->gelar_akhiran}");
    }

    /**
     * Accessor for pas_foto URL
     *
     * @return string|null
     */
    public function getPasFotoUrlAttribute(): ?string
    {
        return $this->pas_foto ? asset('storage/' . $this->pas_foto) : null;
    }

    /**
     * Scope for searching profiles
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_lengkap', 'like', "%{$search}%")
            ->orWhere('nomor_ktp', 'like', "%{$search}%")
            ->orWhere('alamat', 'like', "%{$search}%");
    }

    /**
     * Scope for filtering by jenis kelamin
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $jenisKelamin
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    /**
     * Scope for filtering by pendidikan terakhir
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $pendidikan
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendidikanTerakhir($query, $pendidikan)
    {
        return $query->where('pendidikan_terakhir', $pendidikan);
    }
}
