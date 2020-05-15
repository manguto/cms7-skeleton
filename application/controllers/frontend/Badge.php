<?php
namespace application\controllers\frontend;

use application\models\User;

use application\core\Controller; use application\core\Route; 
use manguto\cms7\libraries\Sessions;
use application\core\Access;
use application\views\frontend\ViewBadge;
  
class Badge extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        // ===================================================================================
        $route->get('/badge', function () {
            self::PrivativeZone();
            ViewBadge::badge();
        });
        // ===================================================================================
        $route->get('/badge/edit', function () {
            self::PrivativeZone();
            { // verifica se a pg esta sendo recarregada por conta de um erro nos dados do formulario
                $user = Sessions::get('ControlBadge', false, true);
                if ($user == false) {
                    $user = Access::getSessionUser();
                }
            }
            ViewBadge::badge_edit($user);
        });
        // ===================================================================================
        $route->post('/badge/edit', function () {
            self::PrivativeZone();
            if (User::checkUpdate($_POST)) {
                AppHeaderLocation('/badge');
            } else {
                AppHeaderLocation('/badge/edit');
            }
        });
        // ===================================================================================
    }
}

?>