<?php
namespace application\controllers\devend;

use manguto\cms7\application\core\Access;
use manguto\cms7\model\ModelHelper;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\application\core\View;

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
            Access::Concierge(["dev"]);
            ModelHelper::Initializer();
            Controller::HeaderLocation('/dev');
        });
    }

    // ##################################################################
    // ##################################################################
    // ##################################################################
}

?>