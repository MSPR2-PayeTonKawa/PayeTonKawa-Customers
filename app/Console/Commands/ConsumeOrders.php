<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumeOrders extends Command
{
    protected $signature = 'events:listen';
    protected $description = 'Consume order events from RabbitMQ (ex: order.created)';

    public function handle()
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'PTK-MessageBroker'),
            (int) env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );

        $channel = $connection->channel();
        $channel->queue_declare('orders', false, true, false, false);

        $this->info(" [*] Waiting for order messages. To exit press CTRL+C");

        $callback = function ($msg) {
            $payload = json_decode($msg->body, true);

            $event = $payload['event'] ?? null;
            $data  = $payload['data'] ?? [];

            $this->info(" [x] Received event: {$event}");

            if ($event === 'order.created') {
                $this->handleOrderCreated($data);
            }
        };

        $channel->basic_consume('orders', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    private function handleOrderCreated(array $orderData): void
    {
        Log::info('Customers service: order created', [
            'order_id'    => $orderData['id'] ?? null,
            'customer_id' => $orderData['customer_id'] ?? null,
        ]);
    }
}
