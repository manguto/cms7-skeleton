<?php
namespace application\controllers\backend;

use manguto\cms7\libraries\Alert;
use manguto\cms7\libraries\Exception;
use application\core\Controller;
use application\core\Route;
use application\models\User;
use application\core\Access;
use application\views\backend\ViewUsers;

class ControllerUsers extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        // ##################################################
        $route->get('/admin/users', function () {
            Access::Concierge("admin");
            ViewUsers::get_admin_users();
        });
        // ##################################################

        $route->get('/admin/users/create', function () {
            Access::Concierge("admin");
            ViewUsers::get_admin_users_create();
        });
        // ##################################################
        $route->post('/admin/users/create', function () {
            Access::Concierge("admin");
            try {
                $user = new User();
                { // fix - form adminzoneaccess (checkbox) & password crypt
                    $_POST['adminzoneaccess'] = ! isset($_POST['adminzoneaccess']) ? 0 : 1;
                    $_POST['password'] = User::password_crypt($_POST['password']);
                }
                $user->SET_DATA($_POST);
                $user->verifyFieldsToCreateUpdate();
                $user->save();
                Alert::setSuccess("Usuário salvo com sucesso!");
                Controller::HeaderLocation("/admin/users");
            } catch (Exception $e) {
                Alert::setDanger($e);
                Controller::HeaderLocation("/admin/users/create");
            }
        });
        // ##################################################
        $route->get('/admin/users/:id', function ($id) {
            Access::Concierge("admin");
            self::PrivateCrudPermission('view', $id);
            ViewUsers::get_admin_user($id);
        });
        // ##################################################
        $route->get('/admin/users/:id/delete', function ($id) {
            Access::Concierge("admin");
            self::PrivateCrudPermission('delete', $id);
            $user = new User($id);
            $user->delete();
            Alert::setSuccess("Usuário removido com sucesso!");
            Controller::HeaderLocation("/admin/users");
        });
        // ##################################################
        $route->get('/admin/users/:id/edit', function ($id) {
            Access::Concierge("admin");
            self::PrivateCrudPermission('edit', $id);
            $user = new User($id);
            ViewUsers::get_admin_user_edit($user);
        });
        // ##################################################
        $route->post('/admin/users/:id/edit', function ($id) {
            Access::Concierge("admin");
            User::PrivateCrudPermission('edit', $id);
            // fix - form adminzoneaccess (checkbox)
            $_POST['adminzoneaccess'] = ! isset($_POST['adminzoneaccess']) ? 0 : 1;
            try {
                $user = new User($id);
                $user->SET_DATA($_POST);
                $user->verifyFieldsToCreateUpdate();
                $user->save();
                Alert::setSuccess("Usuário atualizado com sucesso!");
                Controller::HeaderLocation("/admin/users");
            } catch (Exception $e) {
                Alert::setDanger($e);
                Controller::HeaderLocation("/admin/users/create");
            }
        });
        // ##################################################
    }
}

?>