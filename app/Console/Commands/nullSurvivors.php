<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WagerQuestion;
use App\Models\Survivor;
use App\Models\User;
use Carbon\Carbon;
use App\Models\WagerOption;
use DateTime;
use App\Models\SurvivorLog;


class nullSurvivors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:nullsurvivors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove survivors who havented played';

    public function gradeIt()
    {
      $survivors = SurvivorLog::Where('alive', 1)->where('pool_id', 1)->pluck('user_id')->toArray();
      $bots = User::Where("groupID", 5)->pluck('id')->toArray();

      foreach ($survivors as $survivor) {
        if(!(in_array($survivor, $bots)))
        {
          $realSurvivors[] = $survivor;
        }
      }

      //count($realSurvivors);
        $submitted = Survivor::WhereIn('uid', $realSurvivors)->pluck('uid')->toArray();
          foreach($realSurvivors as $surv) {
           if(!(in_array($surv, $submitted)))
            {
              $nullSurvivors[] = $surv;
            }
          }
        //$nullSurvivors;
        //SurvivorLog::WhereIn('user_id', $nullSurvivors)->update(['alive' => 0]); //real line

    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        return $this->gradeIt();
    }
}


