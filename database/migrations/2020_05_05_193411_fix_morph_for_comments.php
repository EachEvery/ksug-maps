<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use KSUGMap\Comment;
use KSUGMap\Place;
use KSUGMap\Story;

class FixMorphForComments extends Migration
{

    protected $swap = [
        'place' => Place::class,
        'story' => Story::class
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Comment::all()->each(function ($comment) {
            $comment->update([
                'commentable_type' => $this->swap[$comment->commentable_type]
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Comment::all()->each(function ($comment) {
            $comment->update([
                'commentable_type' => array_flip($this->swap)[$comment->commentable_type]
            ]);
        });
    }
}
