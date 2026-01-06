<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'is_read',
        'is_responded',
        'response',
        'responded_at',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_responded' => 'boolean',
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'active',
        'is_read' => false,
        'is_responded' => false,
    ];

    /**
     * Scope a query to only include active inquiries.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include unread inquiries.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read inquiries.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope a query to only include responded inquiries.
     */
    public function scopeResponded($query)
    {
        return $query->where('is_responded', true);
    }

    /**
     * Scope a query to only include unresponded inquiries.
     */
    public function scopeUnresponded($query)
    {
        return $query->where('is_responded', false);
    }

    /**
     * Mark the inquiry as read.
     */
    public function markAsRead()
    {
        return $this->update(['is_read' => true]);
    }

    /**
     * Mark the inquiry as unread.
     */
    public function markAsUnread()
    {
        return $this->update(['is_read' => false]);
    }

    /**
     * Mark the inquiry as responded.
     */
    public function markAsResponded()
    {
        return $this->update([
            'is_responded' => true,
            'responded_at' => now(),
        ]);
    }

    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        $statusClasses = [
            'active' => 'bg-success',
            'pending' => 'bg-warning',
            'resolved' => 'bg-info',
            'spam' => 'bg-secondary',
        ][$this->status] ?? 'bg-secondary';

        return sprintf(
            '<span class="badge %s">%s</span>',
            $statusClasses,
            ucfirst($this->status)
        );
    }
}
