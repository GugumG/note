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
        'category',
        'color',
    ];

    protected $casts = [
        'deadline' => 'date',
        'completed_at' => 'datetime',
        'is_pinned' => 'boolean',
    ];

    /**
     * Get the urgency status of the task.
     * 2 = Telat (Overdue)
     * 1 = Mepet (Due within 3 days)
     * 0 = Normal
     */
    public function getUrgencyLevelAttribute()
    {
        if ($this->status === 'complete' || !$this->deadline) {
            return 0;
        }

        $daysRemaining = now()->startOfDay()->diffInDays($this->deadline->startOfDay(), false);

        if ($daysRemaining < 0) {
            return 2; // Telat
        }

        if ($daysRemaining <= 3) {
            return 1; // Mepet
        }

        return 0;
    }

    /**
     * Get the descriptive status name for the urgency.
     */
    public function getUrgencyLabelAttribute()
    {
        $level = $this->urgency_level;

        if ($level === 2) return 'Telat';
        if ($level === 1) return 'Mepet';
        
        return null;
    }
}
