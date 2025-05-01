<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'user_type',
        'wallet_balance',
        'profile_picture',
        'bio',
        'is_verified',
        'is_admin',
        'is_banned',
        'category'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'wallet_balance' => 'decimal:2',
        'is_verified' => 'boolean'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function liveSessions()
    {
        return $this->hasMany(LiveSession::class);
    }

    public function oneOnOneSessions()
    {
        return $this->hasMany(OneOnOneSession::class, 'influencer_id');
    }

    public function bookedSessions()
    {
        return $this->hasMany(OneOnOneSession::class, 'user_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    public function getFollowingCountAttribute()
    {
        return $this->following()->count();
    }

    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }

    public function isInfluencer()
    {
        return $this->user_type === 'influencer';
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function isVerified()
    {
        return $this->is_verified;
    }

    public function isBanned()
    {
        return $this->is_banned;
    }

    public function getProfileImageUrl()
    {
        return $this->profile_image
            ? asset('storage/' . $this->profile_image)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }
} 