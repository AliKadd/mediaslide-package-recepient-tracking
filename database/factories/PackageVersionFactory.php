<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\PackageVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PackageVersion>
 */
class PackageVersionFactory extends Factory
{
    protected $model = PackageVersion::class;

    public function definition() {
        return [
            'package_id' => Package::factory(),
            'version' => 1,
            'created_by' => 1,
            'notes' => $this->faker->sentence,
        ];
    }
}
