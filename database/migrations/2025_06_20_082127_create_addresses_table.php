<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('number');
            $table->string('number_complement', 10)->nullable();
            $table->string('way_name', 255);
            $table->string('way_type', 45);
            $table->string('city', 255);
            $table->string('zip_code', 20);
            $table->string('country', 45);
            $table->string('latitude', 30)->nullable();
            $table->string('longitude', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
