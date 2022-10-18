<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionTrigger extends Migration
{
    public function up()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS auction_status ON auctions');
        DB::unprepared('
            CREATE OR REPLACE FUNCTION auction_status_change()
                RETURNS TRIGGER
                LANGUAGE PLPGSQL
                AS
            $$
            BEGIN
                IF NEW.finished_at IS NOT NULL THEN
                    SET NEW.status = "dismissed";
                ELSE
                    SET NEW.status = "active";
                END IF;
            END;
            $$
        ');

        DB::unprepared('
            CREATE TRIGGER auction_status
            BEFORE UPDATE ON auctions
            FOR EACH ROW
            EXECUTE PROCEDURE auction_status_change();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS auction_status ON auctions');
        DB::unprepared('DROP FUNCTION IF EXISTS auction_status_change;');
    }
}
