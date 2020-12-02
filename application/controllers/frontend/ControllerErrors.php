<?php
namespace application\controllers\frontend;

use manguto\cms7\application\core\View;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;

class ControllerErrors extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        { // ROTAS
            $route->get('/404', function () {
                View::PageFrontend("_404", [
                    'msg' => ''
                ]);
            });            
        }
    }
}



?>