<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddlFieldsToPlacesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->string('alt_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('alt_text');
        });
    }
}
