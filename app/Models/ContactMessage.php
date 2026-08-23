<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * @property int $id
 * @property string $nombre
 * @property string $email
 * @property string $mensaje
 * @property string|null $read_at
 */
#[TypeScript]
class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'email', 'mensaje',
        'attachment_url', 'attachment_name', 'attachment_public_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // boot() logic moved to ContactMessageObserver
}
