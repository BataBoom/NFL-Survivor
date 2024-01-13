<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\SurvivorLog;
use App\Models\Survivor;
use App\Models\Pool;
use Illuminate\Auth\Access\Response;

class SurvivorPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function store(User $user, Pool $pool) {

        // Test to determine Winner?? Works, ghetto, but for a late season thingy works good
        $aliveRecords = SurvivorLog::alive()->get();
        $aliveUsers = $aliveRecords->pluck('user_id');
        if ($aliveRecords->count() === 1 && $aliveUsers->contains($user->id)) {
        return true;
        } else {
        return false;
        }

    }

    public function viewAny(User $user, Pool $pool) {

        /* Check if user is in the pool */
        if ($user->pools()->where('pool_id', $pool->id)->doesntExist()) {
        return false;
        } else {
        return true;
        }
    }

    public function view(User $user, Pool $pool) {
        
        /* Check if user is in the pool */
        if ($user->pools()->where('pool_id', $pool->id)->doesntExist()) {
        return false;
        }

        /* Check if user is dead */
        if($user->pools()->where('pool_id', $pool->id)->whereNot('alive',1)->exists()) {
        return false;
        }

        /* Double Checking Dead Players: if games have been graded, but pool status has not for dead players */
        $isAlive = $user->mySurvivorPicks()->where('pool', $pool->id)->where('result', false)->exists();
        return !$isAlive;
    }

    public function viewEliminated(User $user, Pool $pool) {

        if($user->pools()->where('pool_id', $pool->id)->doesntExist()) {
        return false;
        }

        $isAlive = $user->mySurvivorPicks()->where('pool', $pool->id)->where('result', false)->exists();
    
        return $isAlive;
    }
}
