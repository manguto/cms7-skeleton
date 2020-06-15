<?php
namespace application\controllers\backend;

use application\views\backend\ViewZzz;
use application\core\Controller;
use application\core\Route;
use application\core\Access;

class ControllerZzz extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/admin/zzz', function () {
            Access::CheckUserProfiles([
                "admin"
            ]);
            ViewZzz::zzz();
        });
    }
}

?>