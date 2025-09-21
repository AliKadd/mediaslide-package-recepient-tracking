<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipient extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name', 'email', 'phone',
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
