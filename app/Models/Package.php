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

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function models() {
        return $this->belongsToMany(ModelProfile::class, 'package_models', 'package_id', 'model_id')
            ->withPivot('notes')
            ->withTimestamps();
    }

    public function packageModels() {
        return $this->hasMany(PackageModel::class);
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
