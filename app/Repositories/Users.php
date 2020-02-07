<?php

namespace KSUGMap\Repositories;

use KSUGMap\User;

class Users
{
    public function thatRecieveNotifications()
    {
        return User::where('admin_notifications', 1)->get();
    }
}
