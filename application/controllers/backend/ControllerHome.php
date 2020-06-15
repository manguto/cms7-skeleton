<?php
namespace application\controllers\backend;

use application\core\View;
use application\core\Controller;
use application\core\Route;
use application\core\Access;

class ControllerHome extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/admin', function () {
            Access::CheckUserProfiles([
                "admin"
            ]);
            View::PageBackend('home');
        });
    }

    static function teste()
    {}
}

?>