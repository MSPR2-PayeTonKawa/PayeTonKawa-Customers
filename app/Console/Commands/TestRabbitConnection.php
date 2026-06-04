<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class TestRabbitConnection extends Command
{
    protected $signature = 'rabbitmq:test';
    protected $description = 'Test RabbitMQ connection';

    public function handle()
    {
        $this->info('Testing RabbitMQ connection...');

        try {
            $host = env('RABBITMQ_HOST', 'PTK-MessageBroker');
            $port = (int) env('RABBITMQ_PORT', 5672);
            $user = env('RABBITMQ_USER', 'guest');
            $password = env('RABBITMQ_PASSWORD', 'guest');

            $this->info("Connecting to: {$host}:{$port} with user: {$user}");

            $connection = new AMQPStreamConnection($host, $port, $user, $password);
            $channel = $connection->channel();

            $this->info('✅ Connection successful!');

            $queue = env('RABBITMQ_QUEUE_CUSTOMERS', 'customers');
            $this->info("Declaring queue: {$queue}");
            $channel->queue_declare($queue, false, true, false, false);

            $this->info('✅ Queue declared successfully!');

            $channel->close();
            $connection->close();

            $this->info('✅ Test completed successfully!');

        } catch (\Throwable $e) {
            $this->error('❌ Connection failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
