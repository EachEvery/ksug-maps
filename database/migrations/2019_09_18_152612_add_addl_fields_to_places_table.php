<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
