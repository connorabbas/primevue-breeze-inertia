<?php

use App\Enums\PurchaseOrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {


    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->char('number', 255)->unique();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->enum('status', PurchaseOrderStatus::getValues())->default(PurchaseOrderStatus::DRAFT->value);
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->decimal('tax_rate', 5, 2)->default(0)->comment('Tax rate percentage');
            $table->decimal('additional_costs', 10, 2)->default(0.00)->comment('Shipping, handling, etc..');
            $table->foreignId('user_id')->constrained();
            $table->timestamp('closed_at')->nullable();

            /** This should adhere to the @PurchasingAddressData format.
             *  We should only ever save a snapshot of the current associated address state,
             *  to maintain historical data integrity.
             *  Keys 10/23/24: billTo shipTo shipFrom returnTo */
            $table->json('addresses')->nullable()->comment('Snapshot of App\Enum\PurchasingAddressData');

            $table->text('special_instructions')->nullable();

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
