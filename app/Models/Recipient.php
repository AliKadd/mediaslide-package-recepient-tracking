<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Recipient extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    
    protected $fillable = [
        'name', 'email', 'phone',
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function packageRecipients() {
        return $this->hasMany(PackageRecipient::class);
    }

    public function events() {
        return $this->hasMany(RecipientEvent::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
