<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'number',
        'subject',
        'ticket_type_id',
        'cc_emails',
        'ticket_status_id',
        'operator_id',
        'entity_id',
        'contact_id',
        'inbox_id',
        'body',
    ];

    protected function casts(): array
    {
        return [
            'cc_emails' => 'array',
        ];
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function ticketStatus(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function inbox(): BelongsTo
    {
        return $this->belongsTo(Inbox::class);
    }

    public function pinnedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_pins')->withTimestamps();
    }

    public function isPinnedBy(User $user): bool
    {
        if ($user->relationLoaded('pinnedTickets')) {
            return $user->pinnedTickets->contains('id', $this->id);
        }

        return $this->pinnedByUsers()->whereKey($user->id)->exists();
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->orderByDesc('created_at');
    }

    public function allCcEmails(): array
    {
        return $this->cc_emails ?? [];
    }
}
