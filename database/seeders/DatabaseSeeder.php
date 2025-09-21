<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Package;
use App\Models\PackageVersion;
use App\Models\PackageVersionModel;
use App\Models\Recipient;
use App\Models\RecipientEvent;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=> bcrypt('test123'),
        ]);

        $recipients = Recipient::factory(10)->create();

        Package::factory(5)
            ->has(
                PackageVersion::factory()
                    ->has(
                        PackageVersionModel::factory()
                            ->count(3),
                            'models'
                    ),
                    'versions'
            )
            ->create()
            ->each(function ($package) use ($recipients) {
                $version = $package->versions()->first();

                foreach ($recipients->random(3) as $recipient) {
                    $packageRecipient = $package->recipients()->create([
                        'package_version_id' => $version->id,
                        'recipient_id' => $recipient->id,
                        'sent_by' => 1,
                        'token' => \Illuminate\Support\Str::random(32),
                        'expires_at' => now()->addDays(7),
                    ]);

                    RecipientEvent::factory(5)->create([
                        'package_id' => $package->id,
                        'package_version_id' => $version->id,
                        'package_recipient_id' => $packageRecipient->id,
                        'recipient_id' => $recipient->id,
                    ]);

                    Comment::factory(2)->create([
                        'package_recipient_id' => $packageRecipient->id,
                        'package_version_id' => $version->id,
                        'recipient_id' => $recipient->id,
                    ]);
                }
            });
    }
}
