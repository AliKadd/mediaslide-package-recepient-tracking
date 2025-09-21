<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageVersion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'package_id', 'version', 'created_by', 'notes',
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function package() {
        return $this->belongsTo(Package::class);
    }

    public function models() {
        return $this->hasMany(PackageVersionModel::class);
    }

    public function recipients() {
        return $this->hasMany(PackageRecipient::class);
    }
}
