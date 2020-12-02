<?php
namespace application\controllers\devend;

use manguto\cms7\application\core\Access;
use application\views\devend\ViewZzz;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;

class ControllerZzz extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/zzz', function () {
            Access::CheckUserProfiles([
                "dev"
            ]);
            ViewZzz::PageDevend('zzz');
        });
    }
}

?>