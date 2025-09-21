<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'created_by', 'title', 'description', 'status',
    ];

    public function models()
    {
        return $this->belongsToMany(ModelProfile::class, 'package_models')
            ->withPivot('notes')
            ->withTimestamps();
    }

    public function versions()
    {
        return $this->hasMany(PackageVersion::class);
    }

    public function recipients()
    {
        return $this->hasMany(PackageRecipient::class);
    }
}
