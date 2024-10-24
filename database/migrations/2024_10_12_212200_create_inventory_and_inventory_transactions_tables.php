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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained();
            $table->unsignedBigInteger('inventoryable_id');
            $table->string('inventoryable_type');
            $table->integer('quantity_onhand')->default(0);
            $table->integer('quantity_intransit')->default(0);
            $table->integer('quantity_backordered')->default(0);
            $table->integer('quantity_allocated')->default(0);
            $table->integer('quantity_reserved')->default(0);
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->unique(['location_id', 'inventoryable_id', 'inventoryable_type'], 'inventory_unique');
        });

        Schema::create(
            'inventory_transactions',
            function (Blueprint $table) {
                $table->id();
                $table->ulid('ulid')->unique();
                $table->morphs('inventoryable');
                $table->unsignedBigInteger('from_location_id')->nullable();
                $table->unsignedBigInteger('to_location_id')->nullable();
                $table->integer('quantity');
                $table->string('transaction_type')->index();
                $table->text('reason')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();
                $table->foreign('from_location_id')->references('id')->on('locations')->onDelete('restrict');
                $table->foreign('to_location_id')->references('id')->on('locations')->onDelete('restrict');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
                $table->foreignId('batch_id')->nullable()->constrained('inventory_transaction_batches');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('inventory_transactions');
    }
};
