<?php

namespace App\Listeners;

use App\Events\AccountDeleted;
use App\Mail\AccountDeletion;
use Illuminate\Support\Facades\Mail;

class SendEmailDeletionNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\AccountDeleted  $event
     *
     * @return void
     */
    public function handle(AccountDeleted $event)
    {
        Mail::to($event->user)->queue(new AccountDeletion());
    }
}
