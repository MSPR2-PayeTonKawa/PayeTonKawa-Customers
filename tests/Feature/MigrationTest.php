<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function addresses_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('addresses'));

        $expectedColumns = [
            'id',
            'number',
            'number_complement',
            'way_name',
            'way_type',
            'city',
            'zip_code',
            'country',
            'latitude',
            'longitude',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('addresses', $column),
                "La colonne {$column} est manquante dans la table addresses"
            );
        }
    }

    /** @test */
    public function companies_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('companies'));

        $expectedColumns = [
            'id',
            'name',
            'email',
            'phone',
            'billing_address_id',
            'shipping_address_id',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('companies', $column),
                "La colonne {$column} est manquante dans la table companies"
            );
        }
    }

    /** @test */
    public function customers_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('customers'));

        $expectedColumns = [
            'id',
            'username',
            'last_name',
            'first_name',
            'email',
            'phone',
            'company_id',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('customers', $column),
                "La colonne {$column} est manquante dans la table customers"
            );
        }
    }

    /** @test */
    public function companies_table_has_foreign_keys_to_addresses()
    {
        $this->assertTrue(Schema::hasTable('companies'));

        $this->assertTrue(Schema::hasColumn('companies', 'billing_address_id'));
        $this->assertTrue(Schema::hasColumn('companies', 'shipping_address_id'));
    }

    /** @test */
    public function customers_table_has_foreign_key_to_companies()
    {
        $this->assertTrue(Schema::hasTable('customers'));
        $this->assertTrue(Schema::hasColumn('customers', 'company_id'));
    }
}
