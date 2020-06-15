<?php
namespace application\controllers\devend;

use application\core\Controller;
use application\core\Route;
use application\core\View;
use application\core\Access;

class ControllerHome extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev', function () {
            Access::CheckUserProfiles([
                "developer"
            ]);
            View::PageDevend('index');
        });
    }
}

?>