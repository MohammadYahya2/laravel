<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            [
                'title' => 'Web Development Fundamentals',
                'description' => 'Learn HTML, CSS, JavaScript and modern web technologies',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Backend Development with Laravel',
                'description' => 'Master PHP and Laravel framework for backend development',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Build mobile apps with React Native and Flutter',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Database Design & Management',
                'description' => 'Learn SQL, database design principles and optimization',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Frontend Frameworks',
                'description' => 'Master React, Vue.js and Angular frameworks',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('courses')->insert($courses);
    }
} 