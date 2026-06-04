<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class RabbitMQPublisher
{
    private ?AMQPStreamConnection $connection = null;
    private $channel = null;

    public function __construct()
    {
        if (!env('RABBITMQ_ENABLED', true)) {
            return;
        }

        try {
            $this->connection = new AMQPStreamConnection(
                env('RABBITMQ_HOST', 'PTK-MessageBroker'),
                (int)   env('RABBITMQ_PORT', 5672),
                env('RABBITMQ_USER', 'guest'),
                env('RABBITMQ_PASSWORD', 'guest'),
                env('RABBITMQ_VHOST', '/'),
                false, 'AMQPLAIN', null, 'en_US',
                (float) env('RABBITMQ_CONN_TIMEOUT', 3.0),
                (float) env('RABBITMQ_RW_TIMEOUT', 3.0),
                null, false,
                (int)   env('RABBITMQ_HEARTBEAT', 30)
            );
            $this->channel = $this->connection->channel();
        } catch (\Throwable $e) {
            Log::warning('RabbitMQ connection failed: ' . $e->getMessage());
        }
    }

    public function publish(string $queue, array $data): void
    {
        if (!$this->channel) {
            Log::warning('RabbitMQ publish skipped (no channel)');
            return;
        }

        $this->channel->queue_declare($queue, false, true, false, false);

        $deliveryMode = defined(AMQPMessage::class . '::DELIVERY_MODE_PERSISTENT')
            ? AMQPMessage::DELIVERY_MODE_PERSISTENT
            : 2;

        $msg = new AMQPMessage(
            json_encode($data, JSON_UNESCAPED_UNICODE),
            [
                'content_type'  => 'application/json',
                'delivery_mode' => $deliveryMode,
            ]
        );

        $this->channel->basic_publish($msg, '', $queue);
    }

    public function publishEvent(string $queue, string $event, array $payload): void
    {
        $this->publish($queue, [
            'event' => $event,
            'data'  => $payload,
            'ts'    => now()->toIso8601String(),
        ]);
    }

    public function __destruct()
    {
        try { $this->channel?->close(); } catch (\Throwable $e) {}
        try { $this->connection?->close(); } catch (\Throwable $e) {}
    }
}
