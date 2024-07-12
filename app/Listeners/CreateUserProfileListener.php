<?php

namespace App\Listeners;

use App\Events\UserRegisteredEvent;
use App\Models\Profile;

class CreateUserProfileListener
{
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param UserRegisteredEvent $event
     * @return void
     */
    public function handle(UserRegisteredEvent $event): void
    {
        Profile::create([
            'user_id' => $event->user->id,
            'solved_quizzes' => 0,
            'score' => 0,
            'achievements' => [],
            'rank' => 'novice',
        ]);
    }
}
