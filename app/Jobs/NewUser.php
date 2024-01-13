<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\SurvivorLog;
use App\Models\Pool;

class NewUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    public $maxExceptions = 3;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
/*
        $freePool = Pool::Where('name', 'Alpha')->get();
        $poolRegistration = SurvivorLog::Create([
        'alive' => true,
        'user_id' => $this->user->id,
        'pool_id' => $freePool->first()->id,
        ]);

        if($poolRegistration->save()) {
        return true;
        } else {
        $this->fail();
        }
*/
return true;
    }
}
