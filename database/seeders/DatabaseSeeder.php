<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\Achievement;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::factory()->create([
            'id' => 1,
            'name' => RolesEnum::ADMIN->value,
        ]);

        Role::factory()->create([
            'id' => 2,
            'name' => RolesEnum::USER->value,
        ]);

        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@adminfactory.com',
            'role_id' => 1,
            'is_verified' => true,
            'last_login_at' => now(),
        ]);

        Tag::factory()->create([
            'title' => 'Active',
            "color" => "#2ecc71",
            "border_color" => "#27ae60",        
            'favicon' => asset('storage/tags/active-user.png')
        ]);
        Tag::factory()->create([
            'title' => 'Task in progress',
            "color" => "#3498db",
            "border_color" => "#2980b9",
            'favicon' => asset('storage/tags/in-progress.png')
        ]);
        Tag::factory()->create([
            'title' => 'Late worker',
            "color" => "#e74c3c",
            "border_color" => "#c0392b",
            'favicon' => asset('storage/tags/late-worker.png')
        ]);
        Tag::factory()->create([
            'title' => 'Creator',
            "color" => "#f39c12",
            "border_color" => "#e67e22",
            'favicon' => asset('storage/tags/creator.png')
        ]);
        Tag::factory()->create([
            'title' => 'Initiator',
            "color" => "#9b59b6",
            "border_color" => "#8e44ad",
            'favicon' => asset('storage/tags/iniciator.png')
        ]);
        Tag::factory()->create([
            'title' => 'Uploaded file',
            "color" => "#1abc9c",
            "border_color" => "#16a085",
            'favicon' => asset('storage/tags/file-uploader.png')
        ]);
        Tag::factory()->create([
            'title' => 'Focused',
            "color" => "#16a085",
            "border_color" => "#1abc9c",
            'favicon' => asset('storage/tags/focused.png')
        ]);
        Tag::factory()->create([
            'title' => 'Finished sprint',
            "color" => "#34495e",
            "border_color" => "#2c3e50",
            'favicon' => asset('storage/tags/finished-sprint.png')
        ]);
        Tag::factory()->create([
            'title' => 'Helping hand',
            "color" => "#f1c40f",
            "border_color" => "#f39c12",
            'favicon' => asset('storage/tags/helping-hand.png')
        ]);
        Tag::factory()->create([
            'title' => 'Mentor',
            "color" => "#2c3e50",
            "border_color" => "#34495e",
            'favicon' => asset('storage/tags/mentor.png')
        ]);
        Tag::factory()->create([
            'title' => 'Most tasks',
            "color" => "#e67e22",
            "border_color" => "#d35400",
            'favicon' => asset('storage/tags/most-tasks.png')
        ]);
        Tag::factory()->create([
            'title' => 'Most karma',
            "color" => "#8e44ad",
            "border_color" => "#9b59b6",
            'favicon' => asset('storage/tags/most-karma.png')
        ]);

        Achievement::factory()->create([
            'title' => 'Just Came',
            "description" => "Welcome to the team!",     
            'favicon' => asset('storage/tags/just-came.png')
        ]);
        Achievement::factory()->create([
            'title' => 'First Task',
            "description" => "Your first step to success!",
            'favicon' => asset('storage/tags/first-task.png')
        ]);
        Achievement::factory()->create([
            'title' => 'Night Bird',
            "description" => "Active after 18pm more then 10 times, do you even rest?",     
            'favicon' => asset('storage/tags/night-bird.png')
        ]);
        Achievement::factory()->create([
            'title' => 'Commited',
            "description" => "Work, work, work... You just completed your 50th task.",     
            'favicon' => asset('storage/tags/commited.png')
        ]);
        Achievement::factory()->create([
            'title' => 'Team player',
            "description" => "We can't do it without you.",     
            'favicon' => asset('storage/tags/team-player.png')
        ]);
        Achievement::factory()->create([
            'title' => 'Maraton',
            "description" => "Watch out, your feet might burn.",     
            'favicon' => asset('storage/tags/maraton.png')
        ]);
        Achievement::factory()->create([
            'title' => 'Visionary',
            "description" => "True leader. Might be a president one day.",     
            'favicon' => asset('storage/tags/visionary.png')
        ]);
        Achievement::factory()->create([
            'title' => 'Legend',
            "description" => "You are irreplaceable!",     
            'favicon' => asset('storage/tags/legend.png')
        ]);
    }
}
