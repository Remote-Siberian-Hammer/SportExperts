<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

require_once dirname(__DIR__,2) . '/app/Domain/Constants/PointSeeder.php';

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $i = 0;
        $userCount = 1;
        $fileId = 300;
        while ($i < EVENT)
        {
            $user = User::find($userCount+1);
            $file = File::find($fileId+1);
            Event::create([
                'user_id' => $user->id,
                'key' => Str::uuid()->toString(),
                'name' => fake()->unique()->name(),
                'description' => fake()->unique()->text(2000),
                'image' => $file->key,
                'start_date' => fake()->date(),
                'start_time' => fake()->time(),
                'expiration_date' => fake()->date(),
                'expiration_time' => fake()->time(),
            ]);
            $userCount = $userCount === (COUNT - 600) ? 1 : $userCount + 1;
            $fileId++;
            $i++;
        }
    }
}
