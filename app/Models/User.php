<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected static function boot() 
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->id))
                $user->id = Str::uuid()->toString();
        });

        static::created(function ($user) {
            $user->chatProfile()->create();
        });
    }

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

   /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function chatProfile() {
        return $this->hasOne(ChatProfile::class);
    }

    public function friendsOfMine() {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id');
    }

    public function friendsOf() {
        return $this->belongsToMany(User::class, 'friend_user', 'friend_id', 'user_id');
    }

    public function messagesSent() {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function messagesReceived() {
        return $this->hasMany(ChatMessage::class, 'receiver_id');
    }

    public function getFriendsAttribute()
    {
        if ( ! array_key_exists('friends', $this->relations)) $this->loadFriends();

        return $this->getRelation('friends');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    protected function loadFriends()
    {
        if ( ! array_key_exists('friends', $this->relations))
        {
            $friends = $this->mergeFriends();

            $this->setRelation('friends', $friends);
        }
    }

    protected function mergeFriends()
    {
        return $this->friendsOfMine->merge($this->friendsOf);
    }
}
