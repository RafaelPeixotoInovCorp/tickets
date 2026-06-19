<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    protected $fillable = [
        'nif',
        'name',
        'phone',
        'mobile',
        'website',
        'email',
        'internal_notes',
    ];

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'entity_contact');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
