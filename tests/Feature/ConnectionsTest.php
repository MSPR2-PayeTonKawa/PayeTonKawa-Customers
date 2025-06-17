<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConnectionsTest extends TestCase
{
    public function test_database_connection()
    {
        try {
            DB::connection()->getPdo();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Database connection failed: ' . $e->getMessage());
        }
    }

    public function test_rabbitmq_connection()
    {
        try {
            $host = config('queue.connections.rabbitmq.host');
            $port = config('queue.connections.rabbitmq.port');
            $user = config('queue.connections.rabbitmq.user');
            $password = config('queue.connections.rabbitmq.password');

            $connection = new AMQPStreamConnection($host, $port, $user, $password);
            $channel = $connection->channel();

            $this->assertTrue(true);

            $channel->close();
            $connection->close();
        } catch (\Exception $e) {
            $this->fail('RabbitMQ connection failed: ' . $e->getMessage());
        }
    }

    public function test_rabbitmq_exchange_and_queue()
    {
        try {
            $host = config('queue.connections.rabbitmq.host');
            $port = config('queue.connections.rabbitmq.port');
            $user = config('queue.connections.rabbitmq.user');
            $password = config('queue.connections.rabbitmq.password');
            $exchange = config('queue.connections.rabbitmq.exchange');
            $queue = config('queue.connections.rabbitmq.queue');

            $connection = new AMQPStreamConnection($host, $port, $user, $password);
            $channel = $connection->channel();

            $channel->exchange_declare($exchange, 'topic', false, true, false);
            $channel->queue_declare($queue, false, true, false, false);

            $this->assertTrue(true);

            $channel->close();
            $connection->close();
        } catch (\Exception $e) {
            $this->fail('RabbitMQ exchange/queue creation failed: ' . $e->getMessage());
        }
    }
}
