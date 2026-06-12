<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_role',
        'client_company',
        'client_logo',
        'content',
        'rating',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    protected static function booted()
    {
        static::saved(fn () => event(new \App\Events\EntityUpdated('testimonial')));
        static::deleted(fn () => event(new \App\Events\EntityUpdated('testimonial')));
    }
}
