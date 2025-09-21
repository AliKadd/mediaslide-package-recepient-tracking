<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageRecipient extends Model
{
    
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'package_id', 'package_version_id', 'recipient_id', 'sent_by', 'token', 'expires_at',
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function version()
    {
        return $this->belongsTo(PackageVersion::class, 'package_version_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function events()
    {
        return $this->hasMany(RecipientEvent::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
