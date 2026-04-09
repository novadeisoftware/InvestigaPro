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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('advisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('payment_method_id')->constrained();
            
            $table->decimal('total_amount', 10, 2);
            $table->decimal('advisor_commission', 10, 2)->default(0);
            
            $table->string('status')->default('pending');
            $table->string('transaction_id')->unique();
            $table->string('receipt_path')->nullable();
            $table->json('payment_data')->nullable();
            
            $table->softDeletes(); // <--- Habilita borrado lógico
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
