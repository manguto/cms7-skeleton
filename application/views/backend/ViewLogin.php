<?php
namespace application\views\backend;

use application\core\View; 

class ViewLogin extends View
{    
    static function get_admin_login()
    {
        self::PageBackend("login");
    }
}