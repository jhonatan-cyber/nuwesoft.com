<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_url',
        'logo_public_id',
        'category',
        'is_active',
        'invert_dark',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'invert_dark' => 'boolean',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    protected static function booted()
    {
        static::saved(fn () => event(new \App\Events\EntityUpdated('technology')));
        static::deleted(fn () => event(new \App\Events\EntityUpdated('technology')));
    }
}
