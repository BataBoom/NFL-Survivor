<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurvivorLog extends Model
{
   use HasFactory;
    protected $table = 'survivor_registrations';
    protected $guarded = [];
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;


    public function poolRegistration()
    {
    return $this->belongsTo(Pool::class, 'pool_id');
    }


    public function scopeAlive($query)
    {
        return $query->where('alive', 1);
    }




}
