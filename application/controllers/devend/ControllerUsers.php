<?php
namespace application\controllers\devend;

use application\core\Access;
use application\models\User;
use manguto\cms7\libraries\Alert;
use manguto\cms7\libraries\Exception;
use application\core\Controller;
use application\core\Route;
use application\views\devend\ViewUsers;
use manguto\cms7\libraries\File;

class ControllerUsers extends Controller
{
    private const filename = APP_REPOSITORY_DIR . 'user.csv';
    
    static function RouteMatchCheck(Route $route)
    {
        // ##################################################
        $route->get('/dev/users', function () {
            Access::Concierge("dev");
            {   
                $users = File::getContent(self::filename);
                $users = utf8_encode($users);
            }
            ViewUsers::get_dev_users_raw($users);
        });        
        // ##################################################
        $route->post('/dev/users/encrypt', function () {
            Access::Concierge("dev");            
            $p = $_POST['password'] ?? false;
            if($p!==false){
                die(User::password_crypt($p));
            }            
        });
        // ##################################################
        $route->post('/dev/users', function () {
            Access::Concierge("dev");            
            //debc($_POST);
            $users = $_POST['users'];
            //debc($users);
            if($users!==false){
                File::writeContent(self::filename, utf8_decode($users));
                Alert::Success("Usuários salvos com sucesso!");
            }else{
                Alert::Warning("Parâmetros não informados.");
            }
            Controller::HeaderLocation('/dev/users');
        });
        
        // ##################################################
        // ##################################################
        // ##################################################
        /*
        // ##################################################
        $route->get('/dev/users', function () {
            Access::Concierge("dev");
            $users = (new User())->search();
            ViewUsers::get_dev_users($users);
        });
        // ##################################################

        $route->get('/dev/user/create', function () {
            Access::Concierge("dev");
            ViewUsers::get_dev_users_create();
        });
        // ##################################################
        $route->post('/dev/user/create', function () {
            Access::Concierge("dev");
            ViewUsers::post_dev_users_create();
        });
        // ##################################################
        $route->get('/dev/user/:id', function ($id) {
            Access::Concierge("dev");
            $user = new User($id);
            ViewUsers::get_dev_user($user);
        });
        // ##################################################
        $route->get('/dev/user/:id/delete', function ($id) {
            Access::Concierge("dev");
            ViewUsers::get_dev_user_delete($id);
        });
        // ##################################################
        $route->get('/dev/user/:id/edit', function ($id) {
            Access::Concierge("dev");
            $user = new User($id);
            ViewUsers::get_dev_user_edit($user);
        });
        // ##################################################
        $route->post('/dev/user/:id/edit', function ($id) {
            Access::Concierge("dev");
            ViewUsers::post_dev_user_edit($id);
        });
        // ##################################################
        /**/
    }

    static function post_dev_users_create()
    {
        // deb($_POST,0);
        $_POST['password'] = User::password_crypt($_POST['password']);
        // deb($_POST);

        try {
            $user = new User();
            $user->SET_DATA($_POST);
            $user->verifyFieldsToCreateUpdate();
            // deb($user);
            $user->save();
            Alert::Success("Usuário salvo com sucesso!");
            Controller::HeaderLocation("/dev/users");
            exit();
        } catch (Exception $e) {
            Alert::Error($e);
            Controller::HeaderLocation("/dev/user/create");
            exit();
        }
    }

    static function post_dev_user_edit($id)
    {

        // deb($_POST);
        try {
            $user = new User($id);
            // deb("$user",0);
            // deb($_POST,0);
            $user->SET_DATA($_POST);
            // deb("$user");
            // deb($user);

            $user->verifyFieldsToCreateUpdate();
            $user->save();
            // deb("$user");

            Alert::Success("Usuário atualizado com sucesso!");
            Controller::HeaderLocation("/dev/users");
            exit();
        } catch (Exception $e) {
            Alert::Error($e);
            Controller::HeaderLocation("/dev/user/create");
            exit();
        }
    }

    static function get_dev_user_delete($id)
    {
        $user = new User($id);
        $user->delete();
        Alert::Success("Usuário removido com sucesso!");
        Controller::HeaderLocation("/dev/users");
        exit();
    }
}

?>