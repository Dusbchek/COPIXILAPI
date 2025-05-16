<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Team;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'To Do'],
            ['name' => 'In Progress'],
            ['name' => 'Done'],
            ['name' => 'Blocked'],
        ];

        foreach ($statuses as $status) {
            TaskStatus::create($status);
        }

        $this->command->info('Task statuses seeded!');

        $users = collect();
        for ($i = 0; $i < 20; $i++) {
            $users->push(User::create([
                'name' => 'User ' . ($i + 1),
                'email' => 'user' . ($i + 1) . '@example.com',
                'password' => Hash::make('password'),
            ]));
        }

        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $users->push($testUser);

        $teams = collect();
        for ($i = 0; $i < 5; $i++) {
            $teams->push(Team::create([
                'name' => 'Team ' . ($i + 1),
            ]));
        }

        $this->command->info('Teams seeded!');

        // Seed Team Members using direct DB inserts to avoid timestamp errors
        $users = User::all();
        $teams->each(function ($team) use ($users) {
            $members = $users->random(rand(3, 8));
            foreach ($members as $member) {
                // Insert only if this user is not already a member of this team
                $exists = DB::table('team_members')
                    ->where('team_id', $team->id)
                    ->where('user_id', $member->id)
                    ->exists();

                if (! $exists) {
                    DB::table('team_members')->insert([
                        'team_id' => $team->id,
                        'user_id' => $member->id,
                    ]);
                }
            }
        });

        $this->command->info('Team members seeded!');

        $tasks = collect();
        $taskStatuses = TaskStatus::all();
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 30; $i++) {
            $tasks->push(Task::create([
                'title' => 'Task ' . ($i + 1),
                'description' => $faker->sentence,
                'status_id' => $taskStatuses->random()->id,
                'due_date' => $faker->dateTimeBetween('now', '+1 month'),
                'user_id' => $users->random()->id,
            ]));
        }
        $this->command->info('Tasks seeded!');

        // Seed task_team pivot table
        $teams = Team::all();
        $tasks->each(function ($task) use ($teams) {
            $taskTeams = $teams->random(rand(0, $teams->count() > 0 ? min(2, $teams->count()) : 0));

            foreach ($taskTeams as $team) {
                $exists = DB::table('task_team')
                    ->where('task_id', $task->id)
                    ->where('team_id', $team->id)
                    ->exists();

                if (! $exists) {
                    DB::table('task_team')->insert([
                        'task_id' => $task->id,
                        'team_id' => $team->id,
                    ]);
                }
            }
        });

        $this->command->info('task_team pivot table seeded!');
    }
}
