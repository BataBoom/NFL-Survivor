<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurvivorSeeder extends Seeder
{
    /**
     * Run the survivor database seeds.
     * php artisan db:seed --class=SurvivorSeeder
     * 
     */
    public function run(): void
    {

        $this->call(SurvivorScheduleSeeder::class);
    }
}
