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
            $table->id();
            $table->json('address_data')->comment('Addresses should adhere to the App\Data\AddressData format');
            $table->timestamps();
        });

        Schema::create('supplier_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->constrained()->cascadeOnDelete();
            $table->string('address_type');
            $table->timestamps();

            // Each supplier can only have one address of each type
            $table->unique(['supplier_id', 'address_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_address');
        Schema::dropIfExists('addresses');
    }
};
