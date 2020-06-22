<?php
namespace application\controllers\devend;

use application\core\Access;
use manguto\cms7\model\ModelHelper;
use application\core\Controller;
use application\core\Route;
use application\core\View;

class ControllerModels extends Controller
{

    static function RouteMatchCheck(Route $route)
    {

        // ----------------------------------------------------------------------
        $route->get('/dev/models', function () {
            Access::Concierge("dev");
            View::PageDevend('models', [
                'models' => ModelHelper::get()
            ]);
        });
        // ----------------------------------------------------------------------
        $route->get('/dev/models/initialize', function () {
            //Access::Concierge(["dev"]);
            ModelHelper::Initializer();
            Controller::HeaderLocation('/dev');
        });
    }

    // ##################################################################
    // ##################################################################
    // ##################################################################
}

?>