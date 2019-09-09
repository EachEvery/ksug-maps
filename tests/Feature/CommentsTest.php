<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use KSUGMap\Story;
use KSUGMap\Comment;

class CommentsTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    /**
     * A basic feature test example.
     */
    public function testExample()
    {
        $story = factory(Story::class)->create();

        $commentFillable = [
            'text' => 'Test Text',
            'author' => 'Test Author',
            'email' => 'test@email.com',
        ];

        $response = $this->post(sprintf('/stories/%s/comments', $story->id), [
            'comment' => $commentFillable,
        ]);

        $comment = Comment::find(json_decode($response->getContent())->id);

        /*
         * Make sure the comment is saved in the
         * database
         */
        $this->assertDatabaseHas('comments', array_merge([
            'story_id' => $story->id,
        ], $commentFillable));

        /*
         * Make sure it doesn't show up on the app
         * since the comment isn't approved yet
         */
        $this->assertTrue(0 === count($this->getStoryPublicStoryComments($story)));

        /*
         * Approve the comment
         * This would normally be done in
         * admin panel
         * */
        $comment->approve();

        /*
         * Then make sure it exists in the front end
         * now that the comment is approved
         */
        $this->assertNotEmpty(
            collect($this->getStoryPublicStoryComments($story))->firstWhere('id', $comment->id)
        );
    }

    public function getStoryPublicStoryComments($story)
    {
        // make sure it doesn't show up in before approval
        $json = collect(json_decode($this->get('/stories')->getContent()));

        return $json->firstWhere('id', $story->id)->approved_comments;
    }
}
