<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipientEvent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'package_id', 'package_version_id', 'package_recipient_id', 'recipient_id', 'model_id', 'event_type', 'data',
    ];

    protected $casts = [
        'data' => 'array',
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

    public function model()
    {
        return $this->belongsTo(ModelProfile::class, 'model_id');
    }

    public function packageRecipient()
    {
        return $this->belongsTo(PackageRecipient::class);
    }
}
