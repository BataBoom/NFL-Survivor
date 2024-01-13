<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\WagerQuestion;
use App\Models\WagerOption;
use App\Models\WagerTeam;
use App\Models\WagerResults;
use App\Models\Pickem as Pick;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Exception;
use DateTime;

class Pickem extends Component
{
    public $week;
    public $game0;
    public $game1;
    public $game2;
    public $game3;
    public $game4;
    public $game5;
    public $game6;
    public $game7;
    public $game8;
    public $game9;
    public $game10;
    public $game11;
    public $game12;
    public $game13;
    public $game14;
    public $game15;
    public $chgWeek;
    public $whatweek;
    public User $user;

    public function mount()
    {

    $this->user = Auth::User();
    $this->week = $this->chgWeek ?? $this->decipherWeek();
    $this->whatweek = $this->decipherWeek();
    }

    public function changeWeek()
    {   

        $this->week = $this->chgWeek ?? $this->decipherWeek();
        $this->allGames($this->week);
        
    }

    public function hydrate() {

        $this->changeWeek();
        $this->allGames($this->week);
    }

    public function isAllowed() {


    }

    public function allGames($week)
    {
    $currentDatetime = Carbon::now();
    /*
    $ScheduleIds = WagerQuestion::where('week', $week)
    ->where('starts_at', '>', $currentDatetime->toDateTimeString())
    ->pluck('game_id')->toArray();
    */
    $ScheduleIds = WagerQuestion::where('week', $week)
    ->pluck('game_id')->toArray();
    $Teams = WagerOption::WhereIn('game_id', $ScheduleIds)->get();
    $Games = WagerQuestion::WhereIn('game_id', $ScheduleIds)->get();
    $hasEnded = Pick::WhereNull('result')->WhereIn('game_id', $ScheduleIds)->exists() ? true : false;

    $options = collect();
    foreach ($Games as $game) {
    $teamIds = $game->gameoptions()->pluck('team_id');
    $teamInfo = WagerTeam::whereIn('team_id', $teamIds)->select('abbreviation', 'name','team_id','color','altColor')->get();

    $combinedData = collect([
        'game' => $game->question,
        'starts' => $game->starts_at,
        'gid' => $game->game_id,
        'mid' => $game->id,
        'info' => $teamInfo,

    ]);
    $options->push($combinedData);
    $gameVotes[] = Pick::MostForGame($game->game_id)->get();

    }
    return [$Games, $options->toArray(), $gameVotes, $hasEnded];
    }

    public function decipherWeek()
    {

    $dateRanges = [
    ['start' => '2023-09-08', 'end' => '2023-09-12'],
    ['start' => '2023-09-13', 'end' => '2023-09-17'],
    ['start' => '2023-09-18', 'end' => '2023-09-24'],
    ['start' => '2023-09-25', 'end' => '2023-09-29'],
    ['start' => '2023-10-01', 'end' => '2023-10-05'],
    ['start' => '2023-10-06', 'end' => '2023-10-10'],
    ['start' => '2023-10-14', 'end' => '2023-10-23'],
    ['start' => '2023-10-24', 'end' => '2023-10-30'],
    ['start' => '2023-10-31', 'end' => '2023-11-06'],
    ['start' => '2023-11-07', 'end' => '2023-11-13'],
    ['start' => '2023-11-14', 'end' => '2023-11-20'],
    ['start' => '2023-11-21', 'end' => '2023-11-27'],
    ['start' => '2023-11-28', 'end' => '2023-12-04'], 
    ['start' => '2023-12-05', 'end' => '2023-12-11'],
    ['start' => '2023-12-12', 'end' => '2023-12-19'],
    ['start' => '2023-12-20', 'end' => '2023-12-25'],
    ['start' => '2023-12-26', 'end' => '2023-12-31'],
    ['start' => '2024-01-01', 'end' => '2024-01-06'],
    ];

    $now = date('Y-m-d'); // Current date, can be customized
    $week = 1;

    foreach ($dateRanges as $i => $range) {
        if ($now >= $dateRanges[$i]['start'] && $now <= $dateRanges[$i]['end']) {
        $week = $i + 1;
        }
    }

    return $week;

    }

    public function submittedPicks() {
        return $this->user->myPickemPicks()->where('week', $this->week)->get();
    }

    public function gradeGame($game, $option) {

        
        $hasNotEnded = WagerResults::Where('game', $game)->doesntExist();
        //$hasNotEnded = false;
         $isOverStatus = true;
        $hasStarted = WagerQuestion::Where('game_id', $game)->value('starts_at');
        //$timestamp = now()->addDays(37);
        $timestamp = now();
        
        if($hasNotEnded && $hasStarted > $timestamp) {
        $team = WagerTeam::Where('team_id', $option)->pluck('name');
        Pick::updateOrCreate(
        ['game_id' => $game, 'uid' => $this->user->id],
        ['game_id' => $game, 
        'uid' => $this->user->id,
        'selection_id' => $option,
        'week' => $this->week,
        'selection' => $team[0],
        ]);
        $isOverStatus = false;
        $this->dispatchBrowserEvent('successvote', ['teamName' => $team, 'weekNum' => $this->week]);
        }

            if($isOverStatus && WagerResults::Where('game', $game)->exists()) {
            $this->dispatchBrowserEvent('votingended', ['status' => 'Concluded, Winner: '.WagerResults::Where('game', $game)->value('winner_name')]); 
            } elseif ($isOverStatus && WagerResults::Where('game', $game)->doesntExist()) {
            $this->dispatchBrowserEvent('votingended', ['status' => 'IN PROGRESS']); 
            }
        
    }

    public function gameResults() {

       
        $ScheduleIds = WagerQuestion::where('week', $this->week)->pluck('game_id')->toArray();
        $gameResults = WagerResults::WhereIn('game_id', $ScheduleIds)->get();


    }

    public function render()
    {   

        $check = $this->allGames($this->week);
        //if($check[0]->first()->week >= $this->decipherWeek()) {
        //dd($this->allGames($this->week));
        return view('livewire.pickem', [
        'allGames' => $this->allGames($this->week),
        'currentWeek' => $this->decipherWeek(),
        'mypicks' => $this->submittedPicks(),
        'whatweek' => $this->decipherWeek(),
        ]);
        /*
        } else {
        return view('livewire.pickem_results', [
        'allGames' => $this->allGames($this->week),
        'currentWeek' => $this->decipherWeek(),
        'mypicks' => $this->submittedPicks(),
        ]);
        }
        */
    }
}
