<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_category_id')->nullable();
            $table->string('label', 32);

            $table->foreign('parent_category_id')->references('id')->on('offers_category')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers_category');
    }
}
