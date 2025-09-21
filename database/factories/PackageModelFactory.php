<?php

namespace Database\Factories;

use App\Models\ModelProfile;
use App\Models\Package;
use App\Models\PackageModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PackageModel>
 */
class PackageModelFactory extends Factory
{
    protected $model = PackageModel::class;

    public function definition() {
        return [
            // 'package_id' => Package::factory(),
            'model_id' => ModelProfile::factory()
        ];
    }
}
