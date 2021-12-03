<?php

namespace App\Providers;

use App\Events\IssueCreatedEvent;
use App\Events\IssueDetachedEvent;
use App\Listeners\IssueNeedAttachmentListener;
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
        IssueCreatedEvent::class => [
            IssueNeedAttachmentListener::class,
        ],
        IssueDetachedEvent::class => [
            IssueNeedAttachmentListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
