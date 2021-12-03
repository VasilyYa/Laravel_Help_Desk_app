<?php

namespace App\Console\Commands;

use App\Mediators\Mediator;
use Illuminate\Console\Command;

class CleanInactiveIssues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issues:clean {--days=7 : time interval of inactivity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean issues, which are inactive during selected time interval';

    private Mediator $mediator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Mediator $mediator)
    {
        parent::__construct();
        $this->mediator = $mediator;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->option('days');
        $issues = $this->mediator->repository->getAllInactive($days);

        $this->info("\n" . now()->format('d.m.Y H:i') . ' - очистка неактивных в течение ' . $days .  'дн. заявок');
        foreach ($issues as $issue) {
            $this->info('Deleting id=' . $issue->id);
            $this->mediator->service->delete($issue->id);
        }

        return Command::SUCCESS;
    }
}
