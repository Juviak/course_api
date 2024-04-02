<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\course;
use App\Models\capability;
use App\Models\skill;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 30) as $index) {
            course::create([
                'courseName' => $faker->sentence,
                'startDate' => $faker->dateTimeThisMonth,
                'endDate' => $faker->dateTimeThisMonth,
                'courseImage'=> null,
            ]);
        }


        foreach (course::all() as $course) {
        
            $capabilities = [];
            foreach (range(1, 2) as $index) {
                $capability = capability::create([
                    'capabilityName' => $faker->word,
                    'courseId' => $course->id,
                ]);

                $capabilities[] = $capability;

                foreach (range(1, 2) as $index) {
                    skill::create([
                        'skillName' => $faker->word,
                        'capabilityId' => $capability->id,
                        'courseId' => $course->id,
                    ]);
                }
            }

            $course->capabilities()->saveMany($capabilities);
        }

    }
}
