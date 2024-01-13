<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pickem;
use App\Models\WagerResults;
use Illuminate\Support\Facades\Log;

class gradePickem extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grade:pickem {week}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'grade pickem picks';

    public function getWeek()
    {
        return $this->argument('week') ?? 1;
    }


    public function newGrader()
    {   

        /* Works perfect, just to be safe use Pool 1 but thinking how to make that better, regardless fantastic refactor! */
        $allPicks = Pickem::where('week', $this->getWeek())->get();

            foreach ($allPicks as $pick) {
                
                if($pick->results === null) {
                continue;
                }
            
            $deepResult = $pick->results;
            $selection = $pick->selection;
                
            $tie = false;
            
            if($deepResult->winner == 35) {
            $tie = true;
            } 

            if($tie) {
            $type = 'Tie';
            $pick->result = 1;
            $pick->save();
            $stringTie = 'YES';
            $this->line('Selection: '.$selection.' Result: '.$deepResult->winner_name.' Tie: '.$stringTie.' Type: '.$type);
            continue; //exit
            }

            if($pick->selection_id == $deepResult->winner) {
             $type = 'Win';
             $pick->result = 1;
             
            } else {
             $type = 'Lose';
             $pick->result = 0;
            }

            $pick->save();

            $stringTie = $tie ? 'yes': 'no';
            $this->line('Selection: '.$selection.' Result: '.$deepResult->winner_name.' Tie: '.$stringTie.' Type: '.$type);
        }

    }
    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->newGrader();

    }
}
