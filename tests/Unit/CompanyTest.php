<?php

namespace Tests\Feature;

use App\Models\Company;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    public function test_returns_name()
    {
        $company = new Company(['name' => 'Test Company']);
        $this->assertEquals('Test Company', $company->name);
    }

    public function test_returns_email(){
        $company = new Company([
            'email' => 'test@company.fr'
        ]);

        $this->assertEquals('test@company.fr', $company->email);
    }

    public function test_returns_phone(){
        $company = new Company([
            'phone' => '0600000000'
        ]);

        $this->assertEquals('0600000000', $company->phone);
    }

    public function test_returns_true_if_company_has_billing_address()
    {
        $company = new Company(['billing_address_id' => 1]);
        $this->assertTrue($company->hasBillingAddress());
    }

    public function test_returns_false_if_company_has_no_billing_address()
    {
        $company = new Company(['billing_address_id' => null]);
        $this->assertFalse($company->hasBillingAddress());
    }

    public function test_returns_true_if_company_has_shipping_address()
    {
        $company = new Company(['shipping_address_id' => 1]);
        $this->assertTrue($company->hasShippingAddress());
    }

    public function test_returns_false_if_company_has_no_shipping_address()
    {
        $company = new Company(['shipping_address_id' => null]);
        $this->assertFalse($company->hasShippingAddress());
    }
}
