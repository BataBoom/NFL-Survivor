<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survivor;
use App\Models\WagerResults;
use App\Jobs\SurvivorGraded;
use Illuminate\Support\Facades\Log;

class gradeSurvivor extends Command
{

    /* BATABOOM */ 

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grade:survivor {week}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'grade survivors picks';

    public function getWeek()
    {
        return $this->argument('week') ?? 'test';
    }

    public function newGrader($pool)
    {   

        /* Works perfect, just to be safe use Pool 1 but thinking how to make that better, regardless fantastic refactor! */
        $allPicks = Survivor::where('week', $this->getWeek())->where('pool', $pool)->get();

            foreach ($allPicks as $pick) {

                if($pick->resultz === null) {
                continue;
                }

            $deepSelection = $pick->user->mySurvivorPicks()->where('pool', $pool)->where('week', $this->getWeek())->first();
            $deepResult = $pick->resultz;
  
            

            $tie = false;
            
            if($deepResult->winner == 35) {
            $tie = true;
            } 

            if($tie) {
            $type = 'Tie';
            $pick->user->mySurvivorPicks()->where('pool', $pool)->where('week', $this->getWeek())->first()->update(['result' => 1]);
            $stringTie = 'YES';
            $this->line('Selection: '.$deepSelection->selection.' Result: '.$deepResult->winner_name.' Tie: '.$stringTie);
            continue; //exit

            }

            if($deepSelection->selection_id == $deepResult->winner) {
             $type = 'Win';
             $pick->user->mySurvivorPicks()->where('pool', $pool)->where('week', $this->getWeek())->first()->update(['result' => 1]);
             $this->line('Winner! '. $deepSelection->selection);
             //echo 'Winner! '. $pick->user->id."\n";
             
            } elseif($deepSelection->selection_id != $deepResult->winner) {
             $type = 'Lose';
             $pick->user->mySurvivorPicks()->where('pool', $pool)->where('week', $this->getWeek())->first()->update(['result' => 0]);
             $pick->user->pools()->where('pool_id', $pool)->first()->update(['alive' => 0]);
             //$pick->result = 0;
             //$pick->save();
             $this->line('Loser! '. $deepSelection->selection);

            }

        

            //SurvivorGraded::dispatch($pick->user, $type, $this->getWeek());

            $stringTie = $tie ? 'yes': 'no';
            //echo $this->line('Selection: '.$deepSelection->selection.' Result: '.$deepResult->winner_name.' Tie: '.$stringTie);
        }

    }
    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pools = [2,1];
        foreach ($pools as $pool)
        {
        $this->newGrader($pool);
        }

    }
}
