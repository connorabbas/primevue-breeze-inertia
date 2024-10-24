<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id');
            $table->foreignId('part_id');
            $table->integer('quantity_ordered');
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->integer('quantity_invoiced')->default(0);
            $table->integer('quantity_received')->default(0);
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['purchase_order_id', 'part_id']);
            $table->index(['purchase_order_id', 'part_id']);
            $table->index(['quantity_ordered', 'quantity_received']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_parts');
    }
};
