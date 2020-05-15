<?php
namespace application\controllers\devend;

use application\models\User; 
use manguto\cms7\libraries\ProcessResult;
use manguto\cms7\libraries\Exception;
use application\core\Controller; use application\core\Route; 
use application\views\devend\ViewUsers;
 
class Users extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/users', function () {
            self::PrivativeDevZone();
            $users = (new User())->search();
            ViewUsers::get_dev_users($users);
        });

        $route->get('/dev/users/create', function () {
            self::PrivativeDevZone();
            ViewUsers::get_dev_users_create();
        });

        $route->post('/dev/users/create', function () {
            self::PrivativeDevZone();
            Users::post_dev_users_create();
        });

        $route->get('/dev/users/:id', function ($id) {
            self::PrivativeDevZone();
            $user = new User($id);
            ViewUsers::get_dev_user($user);  
        }); 

        $route->get('/dev/users/:id/delete', function ($id) {
            self::PrivativeDevZone();
            Users::get_dev_user_delete($id);
        });

        $route->get('/dev/users/:id/edit', function ($id) {
            self::PrivativeDevZone();
            $user = new User($id);
            ViewUsers::get_dev_user_edit($user);
        });

        $route->post('/dev/users/:id/edit', function ($id) {
            self::PrivativeDevZone();
            Users::post_dev_user_edit($id);
        });
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
            ProcessResult::setSuccess("Usuário salvo com sucesso!");
            AppHeaderLocation("/dev/users");
            exit();
        } catch (Exception $e) {
            ProcessResult::setError($e);
            AppHeaderLocation("/dev/users/create");
            exit();
        }
    }

    static function post_dev_user_edit($id)
    {
                
        //deb($_POST);
        try {
            $user = new User($id);
            //deb("$user",0);
            //deb($_POST,0);
            $user->SET_DATA($_POST);
            //deb("$user");
            //deb($user);
            
            $user->verifyFieldsToCreateUpdate();            
            $user->save();
            //deb("$user");
            
            ProcessResult::setSuccess("Usuário atualizado com sucesso!");
            AppHeaderLocation("/dev/users");
            exit();
        } catch (Exception $e) {
            ProcessResult::setError($e);
            AppHeaderLocation("/dev/users/create");
            exit();
        }
    }

    static function get_dev_user_delete($id)
    {
        $user = new User($id);
        $user->delete();
        ProcessResult::setSuccess("Usuário removido com sucesso!");
        AppHeaderLocation("/dev/users");
        exit();
    }
}

?>