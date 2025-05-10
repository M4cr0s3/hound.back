<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Issue;
use App\Models\Role;
use App\Modules\Issue\Enum\IssuePriority;
use App\Modules\Issue\Enum\IssueStatus;
use Illuminate\Database\Seeder;
use Symfony\Component\Uid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'title' => 'Maintainer',
        ]);
        Role::create([
            'title' => 'Developer',
        ]);

        //                Event::create([
        //                    'event_id' => Uuid::v7(),
        //                    'message' => 'Event 1',
        //                    'level' => 'error',
        //                    'type' => 'error',
        //                    'count' => 1,
        //                    'metadata' => [
        //                        'fingerprint' => 1,
        //                        'foo' => 'bar',
        //                    ],
        //                    'project_id' => 1,
        //                    'environment' => 'staging',
        //                    'release' => '1.0.0',
        //                ]);

        //        for ($i = 0; $i < 1000; $i++) {
        //            Issue::create([
        //                'title' => 'Issue '.$i,
        //                'event_id' => 1,
        //                'status' => IssueStatus::OPEN,
        //                'priority' => IssuePriority::HIGH,
        //                'due_date' => now()->addDays(mt_rand(1, 30)),
        //            ]);
        //        }

        //        for ($i = 0; $i < 50; $i++) {
        //            Issue::create([
        //                'title' => 'issue'.now()->format('Y-m-d H:i:s'),
        //                'event_id' => 1,
        //                'status' => IssueStatus::cases()[mt_rand(0, count(IssueStatus::cases()) - 1)],
        //                'priority' => IssuePriority::cases()[mt_rand(0, count(IssuePriority::cases()) - 1)],
        //                'due_date' => now()->addDays(mt_rand(1, 30)),
        //                'created_at' => now()->subHours(20),
        //            ]);
        //        }
    }
}
