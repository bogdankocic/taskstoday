<?php

namespace Database\Seeders;

use App\Enums\AchievementsIdsEnum;
use App\Enums\RolesEnum;
use App\Enums\TagsIdsEnum;
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
            'id' => TagsIdsEnum::Active->value,
            'title' => 'Active',
            "color" => "#2ecc71",
            "border_color" => "#27ae60",        
            'favicon' => 'storage/tags/active-user.png'
        ]);
        Tag::factory()->create([
            'id' => TagsIdsEnum::TaskInProgress->value,
            'title' => 'Task in progress',
            "color" => "#3498db",
            "border_color" => "#2980b9",
            'favicon' => 'storage/tags/in-progress.png'
        ]);
        Tag::factory()->create([
            'id' => TagsIdsEnum::LateWorker->value,
            'title' => 'Late worker',
            "color" => "#e74c3c",
            "border_color" => "#c0392b",
            'favicon' => 'storage/tags/late-worker.png'
        ]);
        Tag::factory()->create([
            'id' => TagsIdsEnum::Creator->value,
            'title' => 'Creator',
            "color" => "#f39c12",
            "border_color" => "#e67e22",
            'favicon' => 'storage/tags/creator.png'
        ]);
        Tag::factory()->create([
            'id' => TagsIdsEnum::Initiator->value,
            'title' => 'Initiator',
            "color" => "#9b59b6",
            "border_color" => "#8e44ad",
            'favicon' => 'storage/tags/iniciator.png'
        ]);
        Tag::factory()->create([
            'id' => TagsIdsEnum::UploadedFile->value,
            'title' => 'Uploaded file',
            "color" => "#1abc9c",
            "border_color" => "#16a085",
            'favicon' => 'storage/tags/file-uploader.png'
        ]);
        Tag::factory()->create([
            'id' => TagsIdsEnum::Focused->value,
            'title' => 'Focused',
            "color" => "#16a085",
            "border_color" => "#1abc9c",
            'favicon' => 'storage/tags/focused.png'
        ]);
        Tag::factory()->create([
            'id' => TagsIdsEnum::FinishedSprint->value,
            'title' => 'Finished sprint',
            "color" => "#34495e",
            "border_color" => "#2c3e50",
            'favicon' => 'storage/tags/finished-sprint.png'
        ]);
        Tag::factory()->create([
            'id' => TagsIdsEnum::HelpingHand->value,
            'title' => 'Helping hand',
            "color" => "#f1c40f",
            "border_color" => "#f39c12",
            'favicon' => 'storage/tags/helping-hand.png'
        ]);
        Tag::factory()->create([
            'id' => TagsIdsEnum::Mentor->value,
            'title' => 'Mentor',
            "color" => "#2c3e50",
            "border_color" => "#34495e",
            'favicon' => 'storage/tags/mentor.png'
        ]);

        Achievement::factory()->create([
            'id' => AchievementsIdsEnum::JustCame->value,
            'title' => 'Just Came',
            "description" => "Welcome to the team!",     
            'favicon' => 'storage/tags/just-came.png'
        ]);
        Achievement::factory()->create([
            'id' => AchievementsIdsEnum::FirstTask->value,
            'title' => 'First Task',
            "description" => "Your first step to success!",
            'favicon' => 'storage/tags/first-task.png'
        ]);
        Achievement::factory()->create([
            'id' => AchievementsIdsEnum::Organizer->value,
            'title' => 'Organizer',
            "description" => "People look up to you.",
            'favicon' => 'storage/tags/organizer.png'
        ]);
        Achievement::factory()->create([
            'id' => AchievementsIdsEnum::NightBird->value,
            'title' => 'Night Bird',
            "description" => "Active after 18pm more then 10 times, do you even rest?",     
            'favicon' => 'storage/tags/night-bird.png'
        ]);
        Achievement::factory()->create([
            'id' => AchievementsIdsEnum::Commited->value,
            'title' => 'Commited',
            "description" => "Work, work, work... You just completed your 50th task.",     
            'favicon' => 'storage/tags/commited.png'
        ]);
        Achievement::factory()->create([
            'id' => AchievementsIdsEnum::TeamPlayer->value,
            'title' => 'Team player',
            "description" => "We can't do it without you.",     
            'favicon' => 'storage/tags/team-player.png'
        ]);
        Achievement::factory()->create([
            'id' => AchievementsIdsEnum::Maraton->value,
            'title' => 'Maraton',
            "description" => "Watch out, your feet might burn.",     
            'favicon' => 'storage/tags/maraton.png'
        ]);
        Achievement::factory()->create([
            'id' => AchievementsIdsEnum::Visionary->value,
            'title' => 'Visionary',
            "description" => "True leader. Might be a president one day.",     
            'favicon' => 'storage/tags/visionary.png'
        ]);
        Achievement::factory()->create([
            'id' => AchievementsIdsEnum::Legend->value,
            'title' => 'Legend',
            "description" => "You are irreplaceable!",     
            'favicon' => 'storage/tags/legend.png'
        ]);
    }
}
