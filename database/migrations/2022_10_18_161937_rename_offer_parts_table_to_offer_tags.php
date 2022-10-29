<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameOfferPartsTableToOfferTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('offers_parts', 'offers_tags');
        Schema::table('offers_tags', function (Blueprint $table) {
            $table->renameColumn('part', 'tag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('offers_tags', 'offers_parts');
        Schema::table('offers_parts', function (Blueprint $table) {
            $table->renameColumn('tag', 'part');
        });
    }
}
