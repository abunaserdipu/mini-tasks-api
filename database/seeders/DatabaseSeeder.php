<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(5)->create()->each(function ($user) {
            Project::factory()->count(3)->create(['user_id' => $user->id])->each(function ($project) {
                Task::factory()->count(6)->create(['project_id' => $project->id]);
            });
        });
    }
}
