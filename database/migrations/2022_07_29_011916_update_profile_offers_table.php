<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProfileOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->float('discount',5,2)->change();
            $table->unsignedBigInteger('hotel_profile_id')->nullable();
            $table->foreign('hotel_profile_id')->references('id')->on('hotel_profiles')->onDelete('cascade');
            $table->date('expires_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->text('discount')->change();
            $table->dropForeign('offers_hotel_profile_id_foreign');
            $table->dropColumn('hotel_profile_id');
            $table->dateTime('expires_at')->change();
        });
    }
}
