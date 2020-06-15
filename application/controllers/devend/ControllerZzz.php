<?php
namespace application\controllers\devend;

use application\core\Access;
use application\views\devend\ViewZzz;
use application\core\Controller;
use application\core\Route;

class ControllerZzz extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/zzz', function () {
            Access::CheckUserProfiles([
                "developer"
            ]);
            ViewZzz::PageDevend('zzz');
        });
    }
}

?>