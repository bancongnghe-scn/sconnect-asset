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
        Schema::create('transfer_assets', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->comment('1: Cấp phát, 2: Thu hồi, 3: Luân chuyển');
            $table->integer('user_id')->nullable();
            $table->integer('org_id');
            $table->integer('asset_id');
            $table->integer('to_user_id')->nullable();
            $table->integer('to_org_id')->nullable();
            $table->integer('created_by');
            $table->string('description', 700)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_assets');
    }
};
https://www.google.com/maps/place/C%E1%BA%AFt+T%C3%B3c+Nam+Thi%C3%AAn+Ho%C3%A0ng+197+Chi%E1%BA%BFn+Th%E1%BA%AFng/data=!4m7!3m6!1s0x3135adfe1e249c9b:0xa2f4b53638ec5b88!8m2!3d20.9783794!4d105.7970377!16s%2Fg%2F11lkpngvgj!19sChIJm5wkHv6tNTERiFvsODa19KI?authuser=0&hl=vi&rclk=1