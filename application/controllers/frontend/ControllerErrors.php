<?php
namespace application\controllers\frontend;

use application\core\View;
use application\core\Controller; use application\core\Route; 

class ControllerErrors extends Controller 
{

    static function RouteMatchCheck(Route $route)
    {
        { // ROTAS
            $route->get('/404', function () {                
                View::PageFrontend("_404");
            });
        }
    }   
    
    
}

?>