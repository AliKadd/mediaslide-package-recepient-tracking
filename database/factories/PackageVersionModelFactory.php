<?php

namespace Database\Factories;

use App\Models\ModelProfile;
use App\Models\PackageVersion;
use App\Models\PackageVersionModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PackageVersionModel>
 */
class PackageVersionModelFactory extends Factory
{
    protected $model = PackageVersionModel::class;

    public function definition() {
        return [
            'package_version_id' => PackageVersion::factory(),
            'model_id' => ModelProfile::factory(),
            'model_snapshot' => [
                'about' => $this->faker->text(50),
                'height' => $this->faker->numberBetween(160, 190),
            ],
        ];
    }
}
