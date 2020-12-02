<?php
namespace application\controllers\frontend;

use manguto\cms7\libraries\Sessions;
use manguto\cms7\application\core\View;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;

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
            $route->get('/hits', function () {                
                header('location:hits.php');
                exit();
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