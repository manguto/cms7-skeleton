<?php
namespace application\controllers\devend;

use application\core\Controller; use application\core\Route; 
use application\core\View;

class Home extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev', function () {
            self::PrivativeDevZone();
            View::PageDevend('index');
        });
    }
}

?>