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
        Schema::create('rods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->decimal('chance_common', 5, 2);
            $table->decimal('chance_rare', 5, 2);
            $table->decimal('chance_sr', 5, 2);
            $table->decimal('chance_ssr', 5, 2);
            $table->decimal('chance_nft', 5, 2);
            $table->decimal('chance_special', 5, 2);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rods');
    }
};
