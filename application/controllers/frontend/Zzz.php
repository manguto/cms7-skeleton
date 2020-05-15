<?php
namespace application\controllers\frontend;

use application\views\frontend\ViewZzz;
use application\core\Controller; use application\core\Route; 

class Zzz extends Controller 
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/zzz', function () {
            self::PrivativeZone();
            ViewZzz::zzz();
        });
    }
}

?>