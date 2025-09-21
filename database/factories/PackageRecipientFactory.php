<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\PackageRecipient;
use App\Models\PackageVersion;
use App\Models\Recipient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PackageRecipient>
 */
class PackageRecipientFactory extends Factory
{
    protected $model = PackageRecipient::class;

    public function definition() {
        return [
            'package_id' => Package::factory(),
            'package_version_id' => PackageVersion::factory(),
            'recipient_id' => Recipient::factory(),
            'sent_by' => 1,
            'token' => Str::random(32),
            'expires_at' => now()->addDays(7),
        ];
    }
}
