<?php

namespace Tests\Feature;

use App\Models\Customer;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function test_returns_full_name()
    {
        $customer = new Customer([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $this->assertEquals('John Doe', $customer->getFullName());
    }

    public function test_returns_email(){
        $customer = new Customer([
            'email' => 'john.doe@gmail.com'
        ]);

        $this->assertEquals('john.doe@gmail.com', $customer->email);
    }

    public function test_returns_phone(){
        $customer = new Customer([
            'phone' => '0600000000'
        ]);

        $this->assertEquals('0600000000', $customer->phone);
    }

    public function test_returns_true_if_customer_has_company()
    {
        $customer = new Customer(['company_id' => 1]);
        $this->assertTrue($customer->hasCompany());
    }

    public function test_returns_false_if_customer_has_no_company()
    {
        $customer = new Customer(['company_id' => null]);
        $this->assertFalse($customer->hasCompany());
    }
}
