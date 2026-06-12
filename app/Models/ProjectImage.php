<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    protected $fillable = [
        'project_id',
        'image_url',
        'public_id',
        'order_index',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    protected static function booted()
    {
        static::saved(fn () => event(new \App\Events\EntityUpdated('project')));
        static::deleted(fn () => event(new \App\Events\EntityUpdated('project')));
    }
}
