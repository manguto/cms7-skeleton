<?php
namespace application\controllers\frontend;

use application\core\Controller;
use application\core\Route;
use manguto\cms7\libraries\ProcessResult;
use application\core\Access;
use application\core\View;

class ControllerLogin extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/login', function () {
            Access::resetSession();
            View::PageFrontend("login");
        });

        $route->post('/login', function () {

            if (Access::checkUserCredentials(($_POST['login'] ?? false), ($_POST['password'] ?? false))) {
                ProcessResult::setSuccess('Credenciais validadas com sucesso! <br/>Bem Vindo(a)!');
                Controller::HeaderLocation('/');
            } else {
                ProcessResult::setError('As credenciais informadas são inválidas!<br/>Tente novamente.');
                Access::resetSession();
                Controller::HeaderLocation('/login');
            }
        });

        $route->get('/logout', function () {
            Access::resetSession();
            Controller::HeaderLocation("/");
        });
    }
}

?>