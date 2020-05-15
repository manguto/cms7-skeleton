<?php
namespace application\views\frontend;

use application\core\View;
use application\core\Access;

class ViewBadge extends View
{

    static function badge()
    {
        View::PageFrontend('badge', [
            'user' => Access::getSessionUser()
        ]);
    }

    static function badge_edit($user)
    {
        View::PageFrontend("badge_edit", [
            'user' => $user
        ]);
    }

}