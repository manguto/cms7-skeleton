<?php
namespace application\controllers\devend;

use application\core\Access;
use application\core\View;
use manguto\cms7\libraries\Exception;
use application\core\Route;

class ControllerToolsModules extends ControllerTools
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/tools/modules', function () {
            Access::CheckUserProfiles([
                "dev"
            ]);
            View::PageDevend('tools_modules', get_defined_vars());
        });

        $route->post('/dev/tools/modules', function () {
            Access::CheckUserProfiles([
                "dev"
            ]);
            // deb($_POST);
            {
                $platform = $_POST['platform'];
                $ModuleName = ucfirst($_POST['modulename']);
            }
            {
                Exception::deb('To Do!');
                // $results = CMSToolsModules::GenerateFile($platform, $ModuleName);
            }
            View::PageDevend('tools_modules_result', get_defined_vars());
        });
    }
}

?>