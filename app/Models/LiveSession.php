<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'scheduled_at',
        'ended_at',
        'is_live',
        'viewers_count',
        'stream_key'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_live' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function viewers()
    {
        return $this->belongsToMany(User::class, 'live_session_viewers')
            ->withTimestamps();
    }

    public function transactions()
    {
        return $this->morphMany(WalletTransaction::class, 'transactionable');
    }
} 