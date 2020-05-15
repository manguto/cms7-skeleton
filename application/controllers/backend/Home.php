<?php
namespace application\controllers\backend;

use application\core\View;
use application\core\Controller; use application\core\Route; 

class Home extends Controller
{
    
    static function RouteMatchCheck(Route $route)
    {
        
        $route->get('/admin', function () {
            self::PrivativeAdminZone();            
            View::PageBackend('home');
        });
    }
    
    static function teste() {
        
    }
}

?>