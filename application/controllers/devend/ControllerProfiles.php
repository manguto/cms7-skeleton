<?php
namespace application\controllers\devend;

use manguto\cms7\application\core\Access;
use manguto\cms7\libraries\Alert;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\libraries\File;
use manguto\cms7\application\core\View;

class ControllerProfiles extends Controller
{
    private const filename = APP_REPOSITORY_DIR . 'profile.csv';
    
    static function RouteMatchCheck(Route $route)
    {
        // ##################################################
        $route->get('/dev/profiles', function () {
            Access::Concierge("dev");
            {   
                $profiles = File::getContent(self::filename);
                $profiles = utf8_encode($profiles);
            }
            View::PageDevend("profiles/profiles_raw", get_defined_vars());            
        });
        // ##################################################
        $route->post('/dev/profiles', function () {
            Access::Concierge("dev");            
            //debc($_POST);
            $profiles = $_POST['profiles'];
            //debc($profiles);
            if($profiles!==false){
                File::writeContent(self::filename, utf8_decode($profiles));
                Alert::Success("Perfis salvos com sucesso!");
            }else{
                Alert::Warning("Parâmetros não informados.");
            }
            Controller::HeaderLocation('/dev/profiles');
        });       
      
    }

   
}

?>