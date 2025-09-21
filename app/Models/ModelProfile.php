<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'models';

    protected $fillable = ['name', 'about', 'image', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_models')
            ->withPivot('notes')
            ->withTimestamps();
    }
}
