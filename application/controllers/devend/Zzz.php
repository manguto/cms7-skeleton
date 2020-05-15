<?php
namespace application\controllers\devend;

use application\views\devend\ViewZzz;
use application\core\Controller; use application\core\Route; 
 
class Zzz extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/zzz', function () {
            self::PrivativeDevZone();
            ViewZzz::PageDevend('zzz');
        });
    }
}

?>