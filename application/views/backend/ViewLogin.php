<?php
namespace application\views\backend;

use manguto\cms7\application\core\View; 

class ViewLogin extends View
{    
    static function get_admin_login()
    {
        self::PageBackend("login");
    }
}