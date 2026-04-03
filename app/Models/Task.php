<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'deadline',
        'status',
        'content',
        'completion_data',
        'completed_at',
        'days_diff',
        'is_pinned',
    ];

    protected $casts = [
        'deadline' => 'date',
        'completed_at' => 'datetime',
        'is_pinned' => 'boolean',
    ];
}
