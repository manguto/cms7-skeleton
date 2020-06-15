<?php
namespace application\controllers\frontend;

use application\core\Controller;
use application\core\Route;
use application\core\Access;
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