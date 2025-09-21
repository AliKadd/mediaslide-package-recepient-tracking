<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ModelProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'models';

    protected $fillable = [
        'name', 'about', 'image', 'metadata'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function packages() {
        return $this->belongsToMany(Package::class, 'package_models', 'model_id')
            ->withPivot('notes')
            ->withTimestamps();
    }

    public function getImageAttribute($value) {
        return asset(Storage::url($value));
    }
}
