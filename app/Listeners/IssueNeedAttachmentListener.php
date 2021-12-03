<?php

namespace App\Listeners;

use App\Events\IssueCreatedEvent;
use App\Events\IssueDetachedEvent;
use App\Jobs\IssueNeedAttachmentJob;
use App\Mail\IssueNeedAttachment;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class IssueNeedAttachmentListener
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
     * @param IssueCreatedEvent|IssueDetachedEvent $event
     * @return void
     */
    public function handle(IssueCreatedEvent|IssueDetachedEvent $event)
    {
        $userRepository = app(UserRepository::class);
        $seniorManagers = $userRepository->getAllSeniorManagers();
        foreach ($seniorManagers as $seniorManager) {

            IssueNeedAttachmentJob::dispatch($event->issue, $seniorManager);
        }
    }
}
