<?php

namespace KSUGMap\Http\Controllers;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use KSUGMap\Repositories\Comments;
use KSUGMap\Repositories\Places;

class CommentController
{
    public function __construct(FilesystemManager $fs, Comments $comments, Places $places)
    {
        $this->comments = $comments;
        $this->places = $places;
        $this->fs = $fs;
    }

    public function store(Request $req)
    {
        $comment = $this->comments->create(
            $req->input('comment')
        );

        return $req->missing('media_tmp_path')
            ? $comment : $this->handleMedia(
                $comment,
                $req->input('media_tmp_path')
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
