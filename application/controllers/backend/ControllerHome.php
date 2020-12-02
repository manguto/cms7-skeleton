<?php
namespace application\controllers\backend;

use manguto\cms7\application\core\View;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\application\core\Access;

class ControllerHome extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/admin', function () {
            Access::Concierge("admin");
            View::PageBackend('home');
        });
    }

}

?>