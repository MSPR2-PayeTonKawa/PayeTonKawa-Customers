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
            $host = config('queue.connections.rabbitmq.host');
            $port = config('queue.connections.rabbitmq.port');
            $user = config('queue.connections.rabbitmq.user');
            $password = config('queue.connections.rabbitmq.password');

            $this->info("Connecting to: {$host}:{$port} with user: {$user}");

            $connection = new AMQPStreamConnection($host, $port, $user, $password);
            $channel = $connection->channel();

            $this->info('✅ Connection successful!');

            $exchange = config('queue.connections.rabbitmq.exchange');
            $queue = config('queue.connections.rabbitmq.queue');

            $this->info("Creating exchange: {$exchange}");
            $channel->exchange_declare($exchange, 'topic', false, true, false);

            $this->info("Creating queue: {$queue}");
            $channel->queue_declare($queue, false, true, false, false);

            $this->info('✅ Exchange and queue created successfully!');

            $channel->close();
            $connection->close();

            $this->info('✅ Test completed successfully!');

        } catch (\Exception $e) {
            $this->error('❌ Connection failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
