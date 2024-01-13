<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Survivor;
use App\Models\User;
use App\Models\WagerQuestion;
use Carbon\Carbon;


class SurvivorRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   

        $week = 1;
        $ScheduleIds = WagerQuestion::where('week', $week)->pluck('game_id')->toArray();
        $Games = WagerQuestion::WhereIn('game_id', $ScheduleIds)->get();

        $randomUsers = User::Where('isBot', true)->get();

        foreach($randomUsers as $user) {
            $uidz[] = $user->id;
        }

        for($i = 0; $i < count($uidz); $i++) {
        $randomSelection = $Games->random()->gameoptions->random();
        $t =  [
        'game_id' => $randomSelection->game_id, 
        'uid' => $uidz[$i],
        'selection_id' => $randomSelection->team_id,
        'week' => $randomSelection->week,
        'selection' => $randomSelection->option,
        'pool' => 1,
        ];
        Survivor::Create($t);
        }
    }
}
