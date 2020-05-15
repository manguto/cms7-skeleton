<?php 
namespace application\controllers\backend;


use manguto\cms7\libraries\ProcessResult;
use manguto\cms7\libraries\Exception;
use application\core\Controller; use application\core\Route; 
use application\views\backend\ViewUsers;
use application\models\User; 

class Users extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/admin/users', function () {
            self::PrivativeAdminZone();
            ViewUsers::get_admin_users();
        });
        
        $route->get('/admin/users/create', function () {
            self::PrivativeAdminZone();
            ViewUsers::get_admin_users_create(); 
        });
        
        $route->post('/admin/users/create', function () {
            self::PrivativeAdminZone();            
            try {
                $user = new User();
                {// fix - form adminzoneaccess (checkbox) & password crypt                    
                    $_POST['adminzoneaccess'] = ! isset($_POST['adminzoneaccess']) ? 0 : 1; 
                    $_POST['password'] = User::password_crypt($_POST['password']);                    
                }   
                $user->SET_DATA($_POST);
                $user->verifyFieldsToCreateUpdate();
                $user->save();
                ProcessResult::setSuccess("Usuário salvo com sucesso!");
                AppHeaderLocation("/admin/users");
            } catch (Exception $e) {
                ProcessResult::setError($e);
                AppHeaderLocation("/admin/users/create");
            }
        });
        
        $route->get('/admin/users/:id', function ($id) {
            self::PrivativeAdminZone();
            self::PrivateCrudPermission('view', $id);            
            ViewUsers::get_admin_user($id);
        });
        
        $route->get('/admin/users/:id/delete', function ($id) {
            self::PrivativeAdminZone();
            self::PrivateCrudPermission('delete', $id);
            $user = new User($id);
            $user->delete();
            ProcessResult::setSuccess("Usuário removido com sucesso!");
            AppHeaderLocation("/admin/users");
        });
        
        $route->get('/admin/users/:id/edit', function ($id) {
            self::PrivativeAdminZone();
            self::PrivateCrudPermission('edit', $id);
            $user = new User($id);
            ViewUsers::get_admin_user_edit($user);
        });
        
        $route->post('/admin/users/:id/edit', function ($id) {
            self::PrivativeAdminZone();
            User::PrivateCrudPermission('edit', $id);
            // fix - form adminzoneaccess (checkbox)
            $_POST['adminzoneaccess'] = ! isset($_POST['adminzoneaccess']) ? 0 : 1;
            try {
                $user = new User($id);
                $user->SET_DATA($_POST);
                $user->verifyFieldsToCreateUpdate();
                $user->save();
                ProcessResult::setSuccess("Usuário atualizado com sucesso!");
                AppHeaderLocation("/admin/users");
            } catch (Exception $e) {
                ProcessResult::setError($e);
                AppHeaderLocation("/admin/users/create");
            }
        });
    }
}

?>