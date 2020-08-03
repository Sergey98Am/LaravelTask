<?php

namespace App\Listerens\Auth;

use App\Events\Auth\WriteComment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\Auth\CommentEmail;
use Redirect, Response, DB, Config;
use Mail;

class LWriteComment
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
     * @param  WriteComment  $event
     * @return void
     */
    public function handle(WriteComment $event)
    {
        Mail::to($event->user->email)->send(new CommentEmail($event->user));
    }
}
