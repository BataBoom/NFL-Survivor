<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survivor extends Model
{
    use HasFactory;

    protected $table = 'survivor';

    protected $guarded = [];

    protected $casts = [];

    public $timestamps = true;

     public function user()
    {
    return $this->belongsTo(User::class, 'uid', 'id');
    }
    public function whichPool()
    {
    return $this->belongsTo(Pool::class, 'pool');
    }
    /*
    public function pool()
    {
    return $this->hasOneThrough(
            SurvivorLog::class,
            User::class,
            'id', // Foreign key on the SurvivorLog table...
            'id', // Foreign key on the users table...
            'uid', // Local key on the Survivor table...
            'id' // Local key on the users table...
        );
    }
    */
    
    public static function getLastGradedPickByUser(User $user)
    {
        return self::where('uid', $user->id)
            ->where('pool', $pool)
            ->whereNotNull('result')
            ->latest()
            ->first();
    }

    public static function getLastPickByUser(User $user, $pool)
    {
        return self::where('uid', $user->id)
            ->where('pool', $pool)
            ->latest()
            ->first();
    }


    public function pick()
    {
    return $this->hasOne(WagerOption::class, 'teamid', 'selection_id');
    }

    public function question()
    {
    return $this->hasOne(WagerQuestion::class, 'gameid', 'gameid');
    }
    
    /* Only Appears as function if result is correct */ 
    public function results()
    {
    return $this->hasOne(WagerResults::class, 'winner', 'selection_id');
    }

    /* Fixed lol but keep results incase its stuck in codebase somewhere for now... */
    public function resultz()
    {
    return $this->hasOne(WagerResults::class, 'game', 'game_id');
    }

}
