<?php

namespace App\Events;

use App\Services\MessageBrokerService;

class CustomerRegisteredEvent
{
    private MessageBrokerService $messageBroker;

    public function __construct()
    {
        $this->messageBroker = new MessageBrokerService();
    }

    public function publish(array $customerData): void
    {
        $this->messageBroker->publishEvent(
            'payetonkawa',
            'customer.registered',
            [
                'customer_id' => $customerData['id'],
                'email' => $customerData['email'],
                'name' => $customerData['name'],
                'registered_at' => now()->toISOString(),
                'event_type' => 'customer_registered'
            ]
        );
    }
}
