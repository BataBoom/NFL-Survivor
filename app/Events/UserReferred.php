<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserReferred
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $referralId;
    public $user;

    /**
     * Create a new event instance.
     */
     public function __construct(string $referralId, User $user)
     {
        $this->referralId = $referralId;
        $this->user = $user;
     }
}
