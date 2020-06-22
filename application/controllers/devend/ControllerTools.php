<?php
namespace application\controllers\devend;

use application\core\Access;
use application\core\Controller;
use application\core\Route;
use application\core\View;

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