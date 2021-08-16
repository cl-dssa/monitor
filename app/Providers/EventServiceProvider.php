<?php

namespace App\Providers;

use App\Events\SuspectCaseCreated;
use App\Events\SuspectCaseReceptionedEvent;
use App\Listeners\SuspectCaseCreatedSendMinsalListener;
use App\Listeners\SuspectCaseReceptionedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SuspectCaseCreated::class => [
            SuspectCaseCreatedSendMinsalListener::class,
        ],
        SuspectCaseReceptionedEvent::class => [
            SuspectCaseReceptionedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }

    public function shouldDiscoverEvents()
    {
        return true;
    }
}
