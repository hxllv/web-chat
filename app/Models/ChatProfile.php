<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatProfile extends Model
{
    use HasFactory;

    protected static function boot() 
    {
        parent::boot();

        static::creating(function ($chatProfile) {
            if (empty($chatProfile->id))
                $chatProfile->id = Str::uuid()->toString();
        });
    }

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

    protected $fillable = [
        'image',
        'description',
        'url'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
