<?php
namespace application\views\frontend;

use manguto\cms7\application\core\View;
use manguto\cms7\application\core\Access;

class ViewBadge extends View
{

    static function badge()
    {
        View::PageFrontend('badge/badge', [
            'user' => Access::getSessionUser()
        ]);
    }

    static function badge_edit($user)
    {
        View::PageFrontend("badge/badge_edit", [
            'user' => $user
        ]);
    }

}