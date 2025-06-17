<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;
use Illuminate\Support\Facades\Log;

class MessageBrokerService
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            config('queue.connections.rabbitmq.host'),
            config('queue.connections.rabbitmq.port'),
            config('queue.connections.rabbitmq.user'),
            config('queue.connections.rabbitmq.password')
        );
        $this->channel = $this->connection->channel();
    }

    public function publishEvent(string $exchange, string $routingKey, array $data): void
    {
        $this->channel->exchange_declare($exchange, 'topic', false, true, false);

        $message = new AMQPMessage(
            json_encode($data),
            ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $this->channel->basic_publish($message, $exchange, $routingKey);

        Log::info("Event published", [
            'exchange' => $exchange,
            'routing_key' => $routingKey,
            'data' => $data
        ]);
    }

    public function consumeEvents(string $queue, string $exchange, array $routingKeys, callable $callback): void
    {
        $this->channel->exchange_declare($exchange, 'topic', false, true, false);
        $this->channel->queue_declare($queue, false, true, false, false);

        foreach ($routingKeys as $routingKey) {
            $this->channel->queue_bind($queue, $exchange, $routingKey);
        }

        $this->channel->basic_consume(
            $queue,
            '',
            false,
            false,
            false,
            false,
            function ($msg) use ($callback) {
                try {
                    $data = json_decode($msg->body, true);
                    $callback($data, $msg->getRoutingKey());
                    $msg->ack();

                    Log::info("Event consumed", [
                        'routing_key' => $msg->getRoutingKey(),
                        'data' => $data
                    ]);
                } catch (\Exception $e) {
                    $msg->nack(false, false);
                    Log::error("Error processing message", [
                        'error' => $e->getMessage(),
                        'routing_key' => $msg->getRoutingKey()
                    ]);
                }
            }
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function __destruct()
    {
        if (isset($this->channel)) {
            $this->channel->close();
        }
        if (isset($this->connection)) {
            $this->connection->close();
        }
    }
}
