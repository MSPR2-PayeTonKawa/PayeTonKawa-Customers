<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EventListenerService;

class StartEventListener extends Command
{
    protected $signature = 'events:listen';
    protected $description = 'Start listening for RabbitMQ events';

    public function handle()
    {
        $this->info('Starting event listener for Customers service...');

        $listener = new EventListenerService();
        $listener->startListening();

        return 0;
    }
}
