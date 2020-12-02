<?php
namespace application\controllers\devend;

use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\application\core\View;
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