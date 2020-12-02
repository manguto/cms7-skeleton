<?php
namespace application\controllers\backend;

use application\views\backend\ViewZzz;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\application\core\Access;

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