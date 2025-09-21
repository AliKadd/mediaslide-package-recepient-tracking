<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'package_recipient_id', 'package_version_id', 'model_id', 'recipient_id', 'body',
    ];

    public function packageRecipient()
    {
        return $this->belongsTo(PackageRecipient::class);
    }

    public function version()
    {
        return $this->belongsTo(PackageVersion::class, 'package_version_id');
    }

    public function model()
    {
        return $this->belongsTo(ModelProfile::class, 'model_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }
}
