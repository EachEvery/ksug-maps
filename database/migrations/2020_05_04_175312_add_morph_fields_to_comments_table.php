<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use KSUGMap\Comment;



class AddMorphFieldsToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->string('commentable_type')->nullable();
            $table->string('commentable_id')->nullable();
            $table->unsignedInteger('place_id')->nullable()->change();
        });

        Comment::all()->each(function ($comment) {
            $comment->update([
                'commentable_type' => 'place',
                'commentable_id' => $comment->place_id
            ]);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('place_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedInteger('place_id')->nullable();
        });

        Comment::all()->each(function ($comment) {
            $comment->update([
                'place_id' => $comment->commentable_id,
            ]);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['commentable_type', 'commentable_id']);
        });
    }
}
