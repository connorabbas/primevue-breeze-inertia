<?php

use App\Enums\PurchaseOrderStatus;
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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->char('number', 255)->unique();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->enum('status', PurchaseOrderStatus::getValues())->default(PurchaseOrderStatus::DRAFT->value);
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            // New columns to store the index of the selected address for each type
            $table->unsignedInteger('bill_to_address_index')->nullable();
            $table->unsignedInteger('ship_from_address_index')->nullable();
            $table->unsignedInteger('ship_to_address_index')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
