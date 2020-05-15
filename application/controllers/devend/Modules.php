<?php
namespace application\controllers\devend;

 
use application\core\Controller; use application\core\Route; 
use application\views\devend\ViewModules;
use application\models\User_module;
 
class Modules extends Controller
{
    
    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/modules', function () {
            self::PrivativeDevZone();
            ViewModules::modules();
        });
        
        $route->get('/dev/modules/:action/:key', function ($action,$key) {
            self::PrivativeDevZone();            
            User_module::externalUserModuleUpdate($action,$key);            
            AppHeaderLocation('/dev/modules');
        });
        
    }
}

?>