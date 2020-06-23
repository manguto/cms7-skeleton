<?php
namespace application\controllers\devend;

use application\core\Controller;
use application\core\Route;
use application\core\View;
use manguto\cms7\model\ModelHelper;

class ControllerSetup extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        // ##################################################
        $route->get('/setup', function () {
            //Access::Concierge("dev");
            View::PageDevend('setup');
        }); 
        // ##################################################
        $route->get('/setup/models/init', function () {
            //Access::Concierge(["dev"]);
            ModelHelper::Initializer();
            Controller::HeaderLocation('/setup');
        });
        // ##################################################
    }
}

?>