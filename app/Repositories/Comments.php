<?php

namespace KSUGMap\Repositories;

use Illuminate\Notifications\ChannelManager as Notifier;
use KSUGMap\Comment;
use KSUGMap\Notifications\CommentCreated;

class Comments
{
    public function __construct(Places $places, Users $users, Notifier $notifier)
    {
        $this->places = $places;
        $this->users = $users;
        $this->notifier = $notifier;
    }

    public function create($fillable)
    {
        return Comment::create($fillable)->tap(function ($comment) {
            $this->notifier->send(
                $this->users->thatRecieveNotifications(),
                new CommentCreated($comment)
            );
        });
    }
}
