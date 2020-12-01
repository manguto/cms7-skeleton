<?php
namespace application\controllers\frontend;

use application\core\Controller;
use application\core\Route;
use manguto\cms7\libraries\Alert;
use application\core\Access;
use application\core\View;

class ControllerLogin extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/login', function () {
            Access::unsetSessionUser();
            View::PageFrontend("login");
        });

        $route->post('/login', function () {

            {//TESTE DE CREDENCIAIS!
                {
                    $email = $_POST['email'] ?? false;
                    $password = $_POST['password'] ?? false;
                }
                $user_id = Access::checkUserCredentials($email,$password);
                //deb($user_id);
            }
            if ($user_id!==false) {
                Access::setSessionUser_id($user_id);
                $user = Access::getSessionUser();
                Alert::Success('Credenciais validadas com sucesso! <br/>Bem Vindo(a) '.$user->getName().'!');                
                Controller::HeaderLocation('/');
            } else {
                Access::unsetSessionUser();
                Alert::Error('As credenciais informadas não são válidas!<br/>Por favor, tente novamente!');                
                Controller::HeaderLocation('/login');
            }
        });

        $route->get('/logout', function () {
            Access::unsetSessionUser();
            Alert::Success('Saída realizada com sucesso!');
            Controller::HeaderLocation("/");
        });
    }
}

?>