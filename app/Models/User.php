<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'contact_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isOperator(): bool
    {
        return $this->role === UserRole::Operator;
    }

    public function isClient(): bool
    {
        return $this->role === UserRole::Client;
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function inboxes(): BelongsToMany
    {
        return $this->belongsToMany(Inbox::class);
    }

    public function pinnedTickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'ticket_pins')->withTimestamps();
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'operator_id');
    }

    public function accessibleEntityIds(): array
    {
        if ($this->isOperator()) {
            return Entity::pluck('id')->all();
        }

        return $this->contact?->entities()->pluck('entities.id')->all() ?? [];
    }

    public function accessibleInboxIds(): array
    {
        if ($this->isOperator()) {
            return $this->inboxes()->pluck('inboxes.id')->all();
        }

        return Inbox::where('is_active', true)->pluck('id')->all();
    }
}
