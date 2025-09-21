<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageModel extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'package_models';
    protected $fillable = [
        'package_id', 'model_id', 'notes'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    public function package() {
        return $this->belongsTo(Package::class);
    }

    public function modelProfile() {
        return $this->belongsTo(ModelProfile::class, 'model_id');
    }
}
