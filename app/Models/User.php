<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Assada\Achievements\Achiever;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use Achiever;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function getRecentlyRegisteredAttribute()
    {
    $fiveMinutesAgo = Carbon::now()->subMinutes(20);
    $userRegistrationTime = $this->created_at;
    return $userRegistrationTime >= $fiveMinutesAgo;
    }

    // Relationship With SurvivorLog
    public function pools() {
        return $this->hasMany(SurvivorLog::class, 'user_id');
    }

    // Relationship With Blogs
    public function blogs() {
        return $this->hasMany(Blogs::class, 'author_id');
    }

         // Relationship With Polls
    public function polls() {
        return $this->hasMany(Poll::class, 'owner_id');
    }

        // Relationship With Votes
    public function votes() {
        return $this->hasMany(Vote::class, 'user_id');
    }

      // Relationship With Settings
    public function settings() {
        return $this->hasMany(Settings::class, 'profile_id');
    }

         // Relationship With Votes
    public function survey() {
        return $this->hasOne(Survey::class, 'user_id');
    }

    public function mySurvivorPicks()
    {
        return $this->hasMany(
            Survivor::class,
            'uid', // Foreign key on the Survivor table...
            'id', // Local key on the Users table...
        )->orderBy('week', 'asc');
    }

    // Relationship With Survivor OLD
    public function survive() {
        return $this->hasMany(Survivor::class, 'uid')->whereNot('result', false)->get();
    }

    public function survivor()
    {
    $isAlive = $this->hasMany(Survivor::class, 'uid')->where('result', false)->exists();
    if ($isAlive === false) {
        return true;
    } else {
        return false;
    }
    }

    public function isSurvivor($pool)
    {
    $poolExists = Survivor::where('pool', $pool)->exists();
    
    if (!$poolExists) {
        return false;
    }

    $isAlive = $this->hasMany(Survivor::class, 'uid')->where('pool', $pool)->whereNot('result', false)->exists();
    
    return $isAlive;
    }

    public function survivorPicks($pool)
    {
    $poolExists = Survivor::where('pool', $pool)->exists();
    
    if (!$poolExists) {
        return false;
    }

    //$isAlive = $this->hasMany(Survivor::class, 'uid')->where('pool', $pool)->pluck('selection_id');
    
    return !$isAlive;
    //return 
    }

    public function survivorPickz($pool)
    {
    $poolExists = Survivor::where('pool', $pool)->exists();
    
    if (!$poolExists) {
        return false;
    }

    return $this->hasMany(Survivor::class, 'uid')->where('pool', $pool)->get();

    }

    public function getReferrals()
    {
    return ReferralProgram::all()->map(function ($program) {
        return ReferralLink::getReferral($this, $program);
    });
    }

    /* Pickem */
    public function myPickemPicks()
    {
        return $this->hasMany(
            Pickem::class,
            'uid', // Foreign key on the Survivor table...
            'id', // Local key on the Users table...
        )->orderBy('week', 'asc');
    }
    
}
