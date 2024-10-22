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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('virtual_type', ['bill_to', 'ship_to'])->nullable();
            $table->json('addresses')->nullable();
            $table->enum('type', ['warehouse', 'supplier', 'rack', 'bin', 'virtual'])->default('virtual');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
