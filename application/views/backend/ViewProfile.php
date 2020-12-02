<?php
namespace application\views\backend;

use manguto\cms7\application\core\View;

class ViewProfile extends View
{

    static function get_admin_profile($user)
    {
        self::PageFrontend("profile", [
            'user' => $user->GET_DATA($extraIncluded = true, $ctrlParametersIncluded = false, $referencesIncluded = true, $singleLevelArray = false),
            'form_action' => '/admin/profile',
            'link_change_password' => '/admin/profile/change-password'
        ]);
    }

    static function get_admin_profile_change_password()
    {   
        self::PageFrontend("profile-change-password", [
            'form_action' => '/admin/profile/change-password'
        ]);        
    }
}