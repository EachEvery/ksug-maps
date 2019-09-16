<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhotoPositionToPlacesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->string('photo_position')->default('center');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('photo_position');
        });
    }
}
