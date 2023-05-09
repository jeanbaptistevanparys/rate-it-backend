<?php

namespace Database\Seeders;

use App\Models\Ratable;
use App\Models\RatableLanguage;
use App\Models\Rating;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        Topic::factory(20)
            ->has(
                Ratable::factory(3)
                    ->has(RatableLanguage::factory(1))
                    ->has(RatableLanguage::factory(1)->dutch())
                    ->afterCreating(function ($ratable) {
                        for ($i=0; $i < 3; $i++) {
                            $userIds = User::pluck('id');
                            $ratingUserIds = $ratable->ratings()->pluck('user_id')->toArray();
                            $uniqueUserIds = array_diff($userIds->toArray(), $ratingUserIds);
                            $randomUserId = Arr::random($uniqueUserIds);
                            Rating::factory()->create([
                                'user_id' => $randomUserId,
                                'ratable_id' => $ratable->id,
                            ]);
                        }
                    })
            )
            ->create([
                'user_id' => function () {
                    return User::inRandomOrder()->first()->id;
                }
            ]);
    }
}
