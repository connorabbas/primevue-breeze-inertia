<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('dimensionables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dimension_id');
            $table->unsignedBigInteger('dimensionable_id');
            $table->string('dimensionable_type');
            $table->timestamps();

            $table->foreign('dimension_id')->references('id')->on('dimensions')->onDelete('cascade');
            $table->index(['dimensionable_id', 'dimensionable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dimensionables');
    }
};
