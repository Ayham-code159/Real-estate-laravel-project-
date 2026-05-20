<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message_type',
        'body',
        'audio_path',
        'read_at',
        'image_path',
    ];

    protected $appends = [
        'audio_url',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getAudioUrlAttribute(): ?string
    {
        if (! $this->audio_path) {
            return null;
        }

        return Storage::disk('public')->url($this->audio_path);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(MessageReaction::class);
    }
}
