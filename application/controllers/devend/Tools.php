<?php
namespace application\controllers\devend;

use application\core\Controller; use application\core\Route; 
use application\core\View;

class Tools extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/tools', function () {
            self::PrivativeDevZone();
            View::PageDevend('tools');
        });
    }
}

?>