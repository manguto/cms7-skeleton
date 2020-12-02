<?php
namespace application\controllers\devend;

use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\application\core\View;
use manguto\cms7\application\core\Access;

class ControllerHome extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        // ##################################################
        $route->get('/dev', function () {
            Access::Concierge("dev");
            View::PageDevend('home');
        });        
        // ##################################################
    }
}

?>