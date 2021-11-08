<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'message'
    ];

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    protected static function boot() 
    {
        parent::boot();

        static::creating(function ($message) {
            if (empty($message->id))
                $message->id = Str::uuid()->toString();
        });
    }

    public function sender() {
        $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        $this->belongsTo(User::class, 'receiver_id');
    }
}
