<?php

namespace App\Listeners;

use App\Events\SuspectCaseCreated;
use App\Jobs\SendSuspectCaseMinsalJob;

class SuspectCaseCreatedSendMinsalListener
{
    public function handle(SuspectCaseCreated $event)
    {
        SendSuspectCaseMinsalJob::dispatch($event->suspectCase);
    }
}
