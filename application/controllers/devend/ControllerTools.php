<?php
namespace application\controllers\devend;

use manguto\cms7\application\core\Access;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\application\core\View;

class ControllerTools extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/tools', function () {
            Access::CheckUserProfiles([
                "dev"
            ]);
            View::PageDevend('tools');
        });
    }
}

?>