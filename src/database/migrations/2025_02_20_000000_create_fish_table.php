<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFishTable extends Migration
{
    public function up()
    {
        Schema::create('fish', function (Blueprint $table) {
            $table->id('FishID');
            $table->string('FishName');
            $table->enum('FishRarity', ['COMMON', 'RARE', 'SR', 'SSR', 'NFT', 'SPECIAL']);
            $table->decimal('FishWorth', 10, 2);
            $table->decimal('FishTokenWorth', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fish');
    }
}