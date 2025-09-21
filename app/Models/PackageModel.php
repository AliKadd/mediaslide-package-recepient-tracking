<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageModel extends Model
{
    use SoftDeletes;

    protected $table = 'package_models';
    protected $fillable = [
        'package_id', 'model_id', 'notes'
    ];
}
