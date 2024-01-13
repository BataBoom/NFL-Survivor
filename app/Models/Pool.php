<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    use HasFactory;
    protected $table = 'pools';
    protected $fillable = ['type', 'cost', 'name', 'status'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    public function survivors()
    {
    return $this->belongsTo(SurvivorLog::class, 'pool_id');
    }

    



}
