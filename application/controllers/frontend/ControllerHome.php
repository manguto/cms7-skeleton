<?php
namespace application\controllers\frontend;

use manguto\cms7\libraries\Sessions;
use application\core\View;
use application\core\Controller;
use application\core\Route;

class ControllerHome extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        { // ROTAS
          // ===============================================
            $route->get('/', function () {
                View::PageFrontend("home");
            });
            // ===============================================
            $route->get('/reset', function () {                
                Sessions::Reset();
                View::PageFrontend("home");
            });
            // ===============================================
            $route->get('/resetall', function () {                
                Sessions::Reset(true);
                View::PageFrontend("home");
            });
            // ===============================================
        }
    }
}

?>