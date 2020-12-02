<?php
namespace application\controllers\frontend;

use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\application\core\Access;
use application\views\frontend\ViewZzz;


class ControllerZzz extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/zzz', function () {
            Access::CheckUserProfiles([
                "user"
            ]);             
            ViewZzz::zzz();
        });
    }
}

?>