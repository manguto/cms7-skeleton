<?php
namespace application\controllers\frontend;

use manguto\cms7\libraries\ProcessResult;
use application\core\Controller; use application\core\Route; 
use application\core\View;
use application\models\User_password_recover;

class Forgot extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        // ======================================================================================================================
        $route->get('/forgot', function () {
            View::PageFrontend("forgot", [
                'form_action' => '/forgot'
            ]);
        });
        // ======================================================================================================================
        $route->post('/forgot', function () {
            
            $result = new User_password_recover();
            {
                {
                    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
                }
                $data_result = $result->setObjectData($email);
            }
            if ($data_result === true) {
                {
                    $email_result = $result->sendEmail();
                }
                if ($email_result === true) {
                    $msg = '';
                    $msg .= "Prezado(a),<br/>";
                    $msg .= "Foi enviada uma mensagem de e-mail com os dados necessários para a sua solicitação.<br/>";
                    $msg .= "Verifique a sua caixa de entrada ('$email'), acesse a mensagem e siga as instruções <br/>";
                    $msg .= "contidas na mesma.<br/>";
                    $msg .= "Att<br/>";
                    ProcessResult::setSuccess($msg);
                } else {
                    ProcessResult::setError($email_result);
                }
            } else {
                ProcessResult::setError($data_result);
            }
            AppHeaderLocation('/login');
        });
        // ======================================================================================================================
        $route->get('/forgot/reset/:code', function ($code) {
            $result = User_password_recover::checkCode__getSelf($code, false);
            if (! is_string($result)) {
                $result->loadReferences(false);
                {
                    $pars = [
                        'user' => $result->getUser(),
                        'code' => $code
                    ];
                }
                View::PageFrontend("forgot-reset", $pars);
            } else {
                ProcessResult::setError($result);
                AppHeaderLocation('/login');
            }
        });
        // ======================================================================================================================
        $route->post('/forgot/reset', function () {            
            //deb($_POST);
            $code = isset($_POST['code']) ? trim($_POST['code']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $password2 = isset($_POST['password2']) ? trim($_POST['password2']) : '';
            //----------------------------------------------------------------------------------
            $result = User_password_recover::checkCode__updateUserPassword($code, $password, $password2);
            //----------------------------------------------------------------------------------            
            if ($result===true) {                
                $msg='A senha do usuário foi atualizada com sucesso!<br/>';
                $msg.='Realize o login no sistema para confirmar esta atualização!<br/>';                                
                ProcessResult::setSuccess($msg);                
            } else {
                ProcessResult::setError($result);                
            }
            AppHeaderLocation('/login');
        });
        // ======================================================================================================================
    }
}

?>