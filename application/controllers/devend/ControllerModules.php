<?php
namespace application\controllers\devend;

use application\core\Access;
use application\core\Controller;
use application\core\Route;
use application\views\devend\ViewModules;
use application\models\User_module;

class ControllerModules extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/modules', function () {
            Access::CheckUserProfiles([
                "developer"
            ]);
            ViewModules::modules();
        });

        $route->get('/dev/modules/:action/:key', function ($action, $key) {
            Access::CheckUserProfiles([
                "developer"
            ]);
            User_module::externalUserModuleUpdate($action, $key);
            Controller::HeaderLocation('/dev/modules');
        });
    }
}

?>