<?php
namespace application\views\devend;


use application\models\User;
use application\core\View;
 
class ViewUsers extends View
{

    static function get_dev_users($users)
    {   
        self::PageDevend("users", [
            'users' => $users
        ]);
    }
    
    static function get_dev_users_create()
    {
        $adminzoneaccess = User::adminzoneaccess;
        $devzoneaccess = User::devzoneaccess;
        $temp = 'usuario' . date("is");        
        self::PageDevend("users-create", get_defined_vars());
    }
    static function get_dev_user($user)
    {   
        self::PageDevend("users-view", get_defined_vars());        
    } 
    
    static function get_dev_user_edit($user)
    {   
        $adminzoneaccess = User::adminzoneaccess;
        $devzoneaccess = User::devzoneaccess;
        self::PageDevend("users-update", get_defined_vars());
    }
}