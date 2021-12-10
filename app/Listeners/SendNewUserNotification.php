<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserNotification;
use App\Models\User;

class SendNewUserNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        dd('listener');
        // $admins = User::whereHas('roles', function ($query) {
            // $query->where('id', 1);
        // })->get();
        $admins = User::where('id', 1)->get();
        Notification::send($admins, new NewUserNotification($event->user));
    }
}
