<?php
namespace application\views\backend;

use application\core\View;
use application\models\User;

 
class ViewUsers extends View
{

    static function get_admin_users()
    {   
        {
            $users = (new User())->search();
        }
        self::PageBackend("users/users",get_defined_vars());
    }
    
    static function get_admin_users_create()
    {
        {   
            $temp = '';
        }
        self::PageBackend("users/user_create",get_defined_vars());
    }
    static function get_admin_user($id)
    {   
        {
            $user = new User($id);
        }
        self::PageBackend("users/user-view",get_defined_vars());
    }
    
    static function get_admin_user_edit($user)
    {   
        {
            $user = $user->GET_DATA($extraIncluded = true, $ctrlParametersIncluded = false, $referencesIncluded = true, $singleLevelArray = false);
        }
        self::PageBackend("users/user-update",get_defined_vars());
    }
}