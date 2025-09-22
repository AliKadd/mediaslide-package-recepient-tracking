<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRecipientEventsPartition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-recipient-events-partition';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nextMonth = now()->startOfMonth()->addMonth();
        $partitionName = 'p' . $nextMonth->format('Ym');
        $afterNextMonth = $nextMonth->addMonth()->format('Y-m-d');

        $sql = "
            ALTER TABLE recipient_events
            REORGANIZE PARTITION pmax INTO (
                PARTITION $partitionName VALUES LESS THAN ('$afterNextMonth'),
                PARTITION pmax VALUES LESS THAN (MAXVALUE)
            );
        ";

        DB::statement($sql);
        $this->info("Partition $partitionName has been added for recipient events table.");
    }
}
