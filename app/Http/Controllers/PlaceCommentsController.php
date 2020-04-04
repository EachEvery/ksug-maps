<?php

namespace KSUGMap\Http\Controllers;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use KSUGMap\Repositories\Comments;
use KSUGMap\Repositories\Places;

class PlaceCommentsController
{
    public function __construct(FilesystemManager $fs, Comments $comments, Places $places)
    {
        $this->comments = $comments;
        $this->places = $places;
        $this->fs = $fs;
    }

    public function index(Request $req)
    {
        $place = $this->places->matchingSlug(
            $req->route('place_slug')
        );

        return $this->comments->forPlace($place);
    }

    public function store(Request $req)
    {
        $place = $this->places->matchingSlug(
            $req->route('place_slug')
        );

        $comment = $this->comments->create(
            array_merge($req->input('comment'), [
                'place_id' => $place->id,
            ])
        );

        return $req->missing('media_tmp_path')
            ? $comment : $this->handleMedia(
                $comment, $req->input('media_tmp_path')
            );
    }

    protected function handleMedia($comment, $tmpPath)
    {
        $permPath = str_replace('tmp/', '', $tmpPath);

        $this->fs->disk('s3')->move($tmpPath, $permPath);

        $comment
            ->addMediaFromDisk($permPath, 's3')
            ->toMediaCollection('comment_media');

        return $comment;
    }
}
