<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageVersionModel extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'package_version_id', 'model_id', 'model_snapshot', 'shortlisted'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    protected $casts = [
        'model_snapshot' => 'array',
    ];

    public function version() {
        return $this->belongsTo(PackageVersion::class, 'package_version_id');
    }

    public function model() {
        return $this->belongsTo(ModelProfile::class, 'model_id');
    }
}
