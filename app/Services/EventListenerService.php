<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class EventListenerService
{
    private MessageBrokerService $messageBroker;

    public function __construct()
    {
        $this->messageBroker = new MessageBrokerService();
    }

    public function startListening(): void
    {
        $this->messageBroker->consumeEvents(
            'customers_service_queue',
            'payetonkawa',
            ['order.created'],
            function (array $data, string $routingKey) {
                $this->handleEvent($data, $routingKey);
            }
        );
    }

    private function handleEvent(array $data, string $routingKey): void
    {
        switch ($routingKey) {
            case 'order.created':
                $this->handleOrderCreated($data);
                break;
            default:
                Log::warning("Unknown event type: {$routingKey}");
        }
    }

    private function handleOrderCreated(array $data): void
    {
        Log::info("Customers service: Order created by customer", [
            'order_id' => $data['order_id'],
            'customer_id' => $data['customer_id'],
            'total_amount' => $data['total_amount']
        ]);

        $this->updateCustomerOrderHistory($data['customer_id'], $data);
    }

    private function updateCustomerOrderHistory(int $customerId, array $orderData): void
    {
        Log::info("Updating customer order history", [
            'customer_id' => $customerId,
            'order_total' => $orderData['total_amount']
        ]);
    }
}
