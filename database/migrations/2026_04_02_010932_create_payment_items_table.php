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
       Schema::create('payment_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('payment_id')->constrained()->onDelete('cascade');
    $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
    
    $table->string('product_type');
    $table->decimal('price', 10, 2);
    $table->integer('quantity')->default(1);
    
    $table->softDeletes(); // <--- Habilita borrado lógico
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_items');
    }
};
