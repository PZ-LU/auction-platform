<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_objects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('object_type_id');
            $table->string('name', 24);
            $table->text('preview_image')->nullable();

            $table->foreign('object_type_id')->references('id')->on('object_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auction_objects');
    }
}
