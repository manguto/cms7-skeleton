<?php
namespace application\controllers\devend;

use manguto\cms7\application\core\Access;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\libraries\Alert;
use application\models\EmManutencao;
use application\views\frontend\ViewZzz;
use manguto\cms7\application\core\View;

class ControllerManutencao extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/manutencao', function () {
            Access::CheckUserProfiles([
                "dev"
            ]);
            {
                $emManutencao = EmManutencao::EmFuncionamento();
            }
            View::PageDevend('manutencao', get_defined_vars());
        });

        $route->post('/dev/manutencao', function () {
            Access::CheckUserProfiles([
                "dev"
            ]);
            {
                if (isset($_POST['motivo'])) {
                    // ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO!
                    // ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO!
                    // ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO!
                    $emManutencao = new EmManutencao();
                    $emManutencao->setMotivo($_POST['motivo']);
                    $emManutencao->save();
                    Alert::Success("Manutenção ativada com sucesso!");
                    Controller::HeaderLocation('/dev/manutencao');
                } else {
                    // DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO!
                    // DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO!
                    // DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO!
                    $emManutencao = new EmManutencao($_POST['id']);
                    $emManutencao->setStatus('inativa');
                    $emManutencao->save();
                    Alert::Success("Manutenção desativada com sucesso!");
                    Controller::HeaderLocation('/dev/manutencao');
                }
                $emManutencao = EmManutencao::EmFuncionamento();
            }
            ViewZzz::PageDevend('manutencao', get_defined_vars());
        });
    }
}

?>