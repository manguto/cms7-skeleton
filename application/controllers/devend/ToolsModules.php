<?php
namespace application\controllers\devend;

use application\core\View;
use manguto\cms7\libraries\Exception;
use application\core\Route;
   

class ToolsModules extends Tools
{

    static function RouteMatchCheck(Route $route)
    {        
        $route->get('/dev/tools/modules', function () {
            self::PrivativeDevZone();
            View::PageDevend('tools_modules',get_defined_vars());
        });        
        
        $route->post('/dev/tools/modules', function () {
            self::PrivativeDevZone(); 
            //deb($_POST);
            {
                $platform = $_POST['platform'];
                $ModuleName = ucfirst($_POST['modulename']);
            }
            {   
                Exception::deb('To Do!');
                //$results = CMSToolsModules::GenerateFile($platform, $ModuleName);
            }
            View::PageDevend('tools_modules_result',get_defined_vars());
        });
    }    
}
 
?>