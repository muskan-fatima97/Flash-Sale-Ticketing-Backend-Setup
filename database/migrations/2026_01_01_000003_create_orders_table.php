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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
                        $table->foreignId('event_id')->constrained();
            $table->foreignId('reservation_id')->unique()->constrained();
            $table->string('idempotency_key', 100);
            $table->unsignedTinyInteger('quantity');
            $table->unsignedInteger('amount');
            $table->string('status',20);
            $table->string('payment_reference',40)->unique();
            $table->timestamps();
            $table->unique(['user_id', 'idempotency_key']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
