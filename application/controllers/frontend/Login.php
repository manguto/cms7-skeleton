<?php
namespace application\controllers\frontend;

use application\core\Controller; use application\core\Route; 
use manguto\cms7\libraries\ProcessResult;
use application\core\Access;
use application\core\View;

class Login extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/login', function () {            
            Access::clearSessionUser();            
            View::PageFrontend("login");
        });

        $route->post('/login', function () {
            $login = isset($_POST['login']) ? trim($_POST['login']) : false;
            $password = isset($_POST['password']) ? trim($_POST['password']) : false;            
            if (Access::checkUserCredentials($login, $password)) {                
                ProcessResult::setSuccess('Credenciais validadas com sucesso! <br/>Bem Vindo(a)!');
                AppHeaderLocation('/');
            } else {                
                ProcessResult::setError('As credenciais informadas são inválidas!<br/>Tente novamente.');
                AppHeaderLocation('/login');
            }
        });

        $route->get('/logout', function () {
            Access::clearSessionUser();
            AppHeaderLocation("/");
        });
    }
}

?>