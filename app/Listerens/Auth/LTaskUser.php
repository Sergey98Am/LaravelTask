<?php

namespace App\Listerens\Auth;

use App\Events\Auth\TaskUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\Auth\Email;
use Redirect, Response, DB, Config;
use Mail;

class LTaskUser
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
     * @param  TaskUser  $event
     * @return void
     */
    public function handle(TaskUser $event)
    {
        Mail::to($event->user->email)->send(new Email($event->user));
    }
}
