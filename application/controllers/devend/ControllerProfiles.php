<?php
namespace application\controllers\devend;

use application\core\Access;
use manguto\cms7\libraries\Alert;
use application\core\Controller;
use application\core\Route;
use manguto\cms7\libraries\Files;
use application\core\View;

class ControllerProfiles extends Controller
{
    private const filename = APP_REPOSITORY_DIR . 'profile.csv';
    
    static function RouteMatchCheck(Route $route)
    {
        // ##################################################
        $route->get('/dev/profiles', function () {
            Access::Concierge("dev");
            {   
                $profiles = Files::getContent(self::filename);
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
                Files::writeContent(self::filename, utf8_decode($profiles));
                Alert::setSuccess("Perfis salvos com sucesso!");
            }else{
                Alert::setWarning("Parâmetros não informados.");
            }
            Controller::HeaderLocation('/dev/profiles');
        });       
      
    }

   
}

?>