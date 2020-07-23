<?php
namespace application\controllers\devend;

use application\core\Access;
use manguto\cms7\libraries\Alert;
use application\core\Controller;
use application\core\Route;
use manguto\cms7\libraries\Files;
use application\core\View;

class ControllerUsersProfiles extends Controller
{
    private const filename = APP_REPOSITORY_DIR . 'user_profile.csv';
    
    static function RouteMatchCheck(Route $route)
    {
        // ##################################################
        $route->get('/dev/users_profiles', function () {
            Access::Concierge("dev");
            {   
                $users_profiles = Files::obterConteudo(self::filename);
                $users_profiles = utf8_encode($users_profiles);
            }
            View::PageDevend("users_profiles/users_profiles_raw", get_defined_vars());            
        });
        // ##################################################
        $route->post('/dev/users_profiles', function () {
            Access::Concierge("dev");            
            //debc($_POST);
            $user_profile = $_POST['user_profile'];
            //debc($user_profile);
            if($user_profile!==false){
                Files::escreverConteudo(self::filename, utf8_decode($user_profile));
                Alert::setSuccess("Perfis dos usuários salvos com sucesso!");
            }else{
                Alert::setWarning("Parâmetros não informados.");
            }
            Controller::HeaderLocation('/dev/users_profiles');
        });       
        // ##################################################
    }

   
}

?>