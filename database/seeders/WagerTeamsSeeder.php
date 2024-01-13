<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WagerTeam;

class WagerTeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $file = json_decode(file_get_contents('https://site.api.espn.com/apis/site/v2/sports/football/nfl/teams'), true);

        $teams = $file['sports'][0]['leagues'][0]['teams'][0];
        
        foreach($teams as $team)
        {

        WagerTeam::Create([
        'team_id' => $team['id'],
        'name' => $team['displayName'],
        'abbreviation' => $team['abbreviation'],
        'league' => 'nfl',
        'color'=> $team['color'],
        'altColor' => $team['alternateColor'],
        'location' => $team['location'],
        ]);


        }
    }
}
