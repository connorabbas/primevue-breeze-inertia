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
        Schema::create('inventory_transaction_batches', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('description')->nullable();
            $table->string('status');
            $table->foreignId('user_id')->constrained()->nullable();
            $table->nullableMorphs('reference');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transaction_batches');
    }
};
