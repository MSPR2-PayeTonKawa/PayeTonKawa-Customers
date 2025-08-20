<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EventListenerService;

class StartEventListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start listening for RabbitMQ events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting event listener for Customer service...');

        $listener = new EventListenerService();
        $listener->startListening();

        return 0;
    }
}
