<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FourOhFourLog extends Model
{
    protected $table = '404_logs';

    protected $fillable = [
        'url',
        'referer',
        'ip',
        'user_agent',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];
}
