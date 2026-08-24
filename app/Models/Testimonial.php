<?php

namespace App\Models;

use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory, LogActivity;

    protected $fillable = [
        'client_name',
        'client_role',
        'client_company',
        'client_logo',
        'content',
        'status',
        'form_token',
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

    public function scopePending($query)
    {
        return $query->where('status', 'pending')->latest();
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // boot() logic moved to TestimonialObserver
}
