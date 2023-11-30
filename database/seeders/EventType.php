<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Meeting with an Expert','Question-Answer','Conference','Webinare'];
        for ($i = 0; $i < 4; $i++) {
            DB::table('event_types')->insert([
                'title' => $types[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
