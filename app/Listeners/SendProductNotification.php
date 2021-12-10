<?php

namespace App\Listeners;

use App\Events\ProductEvent;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\NewUserNotification;
use App\Models\User;
use App\Notifications\ProductNotification;

class SendProductNotification
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
     * @param  ProductEvent  $event
     * @return void
     */
    public function handle(ProductEvent $event)
    {
        // dd('ia ma in listener');
        $admins = User::where('id', 1)->get();
        Notification::send($admins, new ProductNotification($event->product));
    }




    // * @param  ProductEvent  $event
    //  * @return void
    //  */
    // public function handle(ProductEvent $event)
    // {
    //     $admins = User::where('id', 1)->get();

    //     Notification::send($admins, new ProductNotification($event->product));
    // }
}
