<?php 
namespace application\controllers\backend;

use application\views\backend\ViewZzz;
use application\core\Controller; use application\core\Route; 

class Zzz extends Controller 
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/admin/zzz', function () {
            self::PrivativeAdminZone();
            ViewZzz::zzz();
        });
    }
}

?>