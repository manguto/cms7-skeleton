<?php
namespace application\controllers\devend;
 

use application\core\Controller; use application\core\Route; 

use manguto\cms7\libraries\ProcessResult;
use application\models\EmManutencao;
use application\views\frontend\ViewZzz;


class Manutencao extends Controller  
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/manutencao', function () {
            self::PrivativeDevZone();
            {
                $emManutencao = EmManutencao::EmFuncionamento();
            }            
            ViewZzz::PageDevend('manutencao',get_defined_vars());
        });
        
        $route->post('/dev/manutencao', function () {
            self::PrivativeDevZone();
            {
                if(isset($_POST['motivo'])){
                    //ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO!
                    //ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO!
                    //ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO! - ATIVACAO!
                    $emManutencao = new EmManutencao();
                    $emManutencao->setMotivo($_POST['motivo']);
                    $emManutencao->save();
                    ProcessResult::setSuccess("Manutenção ativada com sucesso!");
                    AppHeaderLocation('/dev/manutencao');                    
                }else{
                    //DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO!
                    //DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO!
                    //DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO! - DESATIVACAO!
                    $emManutencao = new EmManutencao($_POST['id']);
                    $emManutencao->setStatus('inativa');
                    $emManutencao->save();
                    ProcessResult::setSuccess("Manutenção desativada com sucesso!");
                    AppHeaderLocation('/dev/manutencao');      
                }
                $emManutencao = EmManutencao::EmFuncionamento();
            }            
            ViewZzz::PageDevend('manutencao',get_defined_vars());
        });
        
    }
}

?>