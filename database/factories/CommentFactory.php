<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\ModelProfile;
use App\Models\PackageRecipient;
use App\Models\PackageVersion;
use App\Models\Recipient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition() {
        return [
            'package_recipient_id' => PackageRecipient::factory(),
            'package_version_id' => PackageVersion::factory(),
            'model_id' => ModelProfile::factory(),
            'recipient_id' => Recipient::factory(),
            'body' => $this->faker->sentence,
        ];
    }
}
