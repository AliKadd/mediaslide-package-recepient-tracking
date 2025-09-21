<?php

namespace Database\Factories;

use App\Models\ModelProfile;
use App\Models\Package;
use App\Models\PackageRecipient;
use App\Models\PackageVersion;
use App\Models\Recipient;
use App\Models\RecipientEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipientEvent>
 */
class RecipientEventFactory extends Factory
{
    protected $model = RecipientEvent::class;

    public function definition() {
        return [
            'package_id' => Package::factory(),
            'package_version_id' => PackageVersion::factory(),
            'package_recipient_id' => PackageRecipient::factory(),
            'recipient_id' => Recipient::factory(),
            'model_id' => ModelProfile::factory(),
            'event_type' => $this->faker->randomElement([
                'view_package', 'view_model', 'shortlist', 'download', 'comment'
            ]),
            'data' => [
                'ip' => $this->faker->ipv4,
                'user_agent' => $this->faker->userAgent,
            ],
        ];
    }
}
