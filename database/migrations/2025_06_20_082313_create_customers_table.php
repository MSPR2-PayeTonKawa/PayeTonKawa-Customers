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
        Schema::create('customers', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('username', 45)->unique();
            $table->string('last_name', 45);
            $table->string('first_name', 45);
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->datetime('password_updated_at')->useCurrent()->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
