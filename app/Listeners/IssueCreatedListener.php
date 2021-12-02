<?php

namespace App\Listeners;

use App\Events\IssueCreatedEvent;
use App\Mail\IssueCreated;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class IssueCreatedListener
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
     * @param IssueCreatedEvent $event
     * @return void
     */
    public function handle(IssueCreatedEvent $event)
    {
        $userRepository = app(UserRepository::class);
        $seniorManagers = $userRepository->getAllSeniorManagers();
        foreach ($seniorManagers as $seniorManager) {

            Mail::to($seniorManager)->send(new IssueCreated($event->issue, $seniorManager));
        }
    }
}
