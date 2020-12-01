<?php
namespace application\controllers\frontend;

use application\models\User;

use application\core\Controller; use application\core\Route; 
use application\core\Access;
use application\views\frontend\ViewBadge;
  
class ControllerBadge extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        // ===================================================================================
        $route->get('/badge', function () {
            Access::Concierge("user");
            ViewBadge::badge();
        });
        // ===================================================================================
        $route->get('/badge/edit', function () {
            Access::Concierge("user");
            { // verifica se a pg esta sendo recarregada por conta de um erro nos dados do formulario                
                $user = Access::getSessionUser();
                /*if ($user == false) {
                    $user = Access::getSessionUser();
                }*/
            }
            ViewBadge::badge_edit($user);
        });
        // ===================================================================================
        $route->post('/badge/edit', function () {
            Access::Concierge("user");
            if (User::checkUpdate($_POST)) {
                Controller::HeaderLocation('/badge');
            } else {
                Controller::HeaderLocation('/badge/edit');
            }
        });
        // ===================================================================================
    }
}

?>