<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneOnOneSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'influencer_id',
        'user_id',
        'title',
        'description',
        'price',
        'scheduled_at',
        'ended_at',
        'status',
        'meeting_link'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'ended_at' => 'datetime',
        'price' => 'decimal:2'
    ];

    public function influencer()
    {
        return $this->belongsTo(User::class, 'influencer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->morphMany(WalletTransaction::class, 'transactionable');
    }
} 