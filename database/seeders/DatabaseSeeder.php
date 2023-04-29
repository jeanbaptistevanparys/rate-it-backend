<?php

namespace Database\Seeders;

use App\Models\Ratable;
use App\Models\RatableLanguage;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Topic::factory(2)
            ->has(
                Ratable::factory(3)
                    ->has(RatableLanguage::factory(1))
                    ->has(RatableLanguage::factory(1)->dutch())
            )
            ->create();
    }
}
