<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'email',
        'mensaje',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saved(fn () => event(new \App\Events\EntityUpdated('message')));
        static::deleted(fn () => event(new \App\Events\EntityUpdated('message')));
    }
}
