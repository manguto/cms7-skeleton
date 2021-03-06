<?php
namespace application\controllers\devend;

use manguto\cms7\application\core\Access;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use application\views\devend\ViewModules;
use application\models\User_module;

class ControllerModules extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/modules', function () {
            Access::CheckUserProfiles([
                "dev"
            ]);
            ViewModules::modules();
        });

        $route->get('/dev/modules/:action/:key', function ($action, $key) {
            Access::CheckUserProfiles([
                "dev"
            ]);
            User_module::externalUserModuleUpdate($action, $key);
            Controller::HeaderLocation('/dev/modules');
        });
    }
}

?>