<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Event extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            'eventtype_id' => rand(1, 4),
            'name' => 'First event',
            'description' => 'First event description',
            'location' => 'First event location',
            'day' => '07-12-2023',
            'time' => '11:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('events')->insert([
            'eventtype_id' => rand(1, 4),
            'name' => 'Second event',
            'description' => 'Second event description',
            'location' => 'Second event location',
            'day' => '19-12-2023',
            'time' => '12:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('events')->insert([
            'eventtype_id' => rand(1, 4),
            'name' => 'Third event',
            'description' => 'Third event description',
            'location' => 'Third event location',
            'day' => '25-12-2023',
            'time' => '14:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('events')->insert([
            'eventtype_id' => rand(1, 4),
            'name' => 'Forth event',
            'description' => 'Forth event description',
            'location' => 'Forth event location',
            'day' => '07-01-2024',
            'time' => '17:40',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
