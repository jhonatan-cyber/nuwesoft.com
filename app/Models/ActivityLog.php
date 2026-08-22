<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'subject_type',
        'subject_id',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    // ── Helper methods ──

    public static function log(
        string $type,
        string $description,
        ?Model $subject = null,
        array $properties = [],
    ): static {
        return static::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'properties' => $properties,
        ]);
    }

    public static function created(Model $subject, string $extra = ''): static
    {
        $name = $subject->name ?? $subject->title ?? '#' . $subject->id;

        return static::log('created', 'Creó ' . class_basename($subject) . " \"{$name}\" {$extra}", $subject);
    }

    public static function updated(Model $subject, string $extra = ''): static
    {
        $name = $subject->name ?? $subject->title ?? '#' . $subject->id;

        return static::log('updated', 'Actualizó ' . class_basename($subject) . " \"{$name}\" {$extra}", $subject);
    }

    public static function deleted(Model $subject): static
    {
        $name = $subject->name ?? $subject->title ?? '#' . $subject->id;

        return static::log('deleted', 'Eliminó ' . class_basename($subject) . " \"{$name}\"", $subject);
    }
}
