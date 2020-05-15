<?php
namespace application\controllers\devend;

use manguto\cms7\libraries\ProcessResult;
use application\views\devend\ViewRepository;
use manguto\cms7\libraries\Exception;
use application\core\Controller; 
use application\core\Route; 
class Repository extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/repository', function () {
            self::PrivativeDevZone();
            ViewRepository::repository();
        });

        $route->get('/dev/repository/:repository', function ($repository) {
            self::PrivativeDevZone();
            ViewRepository::repository_view($repository);
        });

        $route->get('/dev/repository/:repository/:id/view', function ($repository, $id) {
            self::PrivativeDevZone();
            // deb($repository,0); deb($id);
            {
                //$repositoryNameCall = Repository::getObjectClassname($repository);
                throw new Exception("");
                
                // deb($repositoryNameCall);
                $register = new $repositoryNameCall($id);
                $register->replaceReferences();
                $register = $register->GET_DATA($extraIncluded = false, $ctrlParametersIncluded = false, $referencesIncluded = true, $singleLevelArray = false);
                // deb($register);
            }
            ViewRepository::repository_register_view($repository, $register);
        });

        $route->get('/dev/repository/:repository/:id/delete', function ($repository, $id) {
            self::PrivativeDevZone();
            // deb($repository,0); deb($id);
            {
                //$repositoryNameCall = Repository::getObjectClassname($repository);
                throw new Exception("");
                
                // deb($repositoryNameCall);
                $register = new $repositoryNameCall($id);
                // deb($register);
                $register->delete();
                ProcessResult::setSuccess("Registro removido com sucesso.");
                AppHeaderLocation("/dev/repository/$repository");
                exit();
            }
        });

        $route->get('/dev/repository/:repository/:id/edit', function ($repository, $id) {
            self::PrivativeDevZone();
            // deb($repository,0); deb($id);
            {
                //$repositoryNameCall = Repository::getObjectClassname($repository);
                throw new Exception("");
                // deb($repositoryNameCall);
                $register = new $repositoryNameCall($id);
                // deb($register);
                $register = $register->GET_DATA($extraIncluded = false, $ctrlParametersIncluded = false, $referencesIncluded = true, $singleLevelArray = false);
                // deb($register);
            }
            ViewRepository::repository_register_edit($repository, $register);
        });

        $route->post('/dev/repository/:repository/:id/edit', function ($repository, $id) {
            self::PrivativeDevZone();
            // deb($repository,0); deb($id);
            // deb($_POST);
            {
                //$repositoryNameCall = Repository::getObjectClassname($repository);
                throw new Exception("");
                // deb($repositoryNameCall);
                { // success msg
                    if ($id == 0) {
                        $msg = 'cadastrado';
                    } else {
                        $msg = 'salvo';
                    }
                    $msg = "Registro $msg com sucesso!";
                }
                $register = new $repositoryNameCall($id);
                $register->SET_DATA($_POST);
                $register->save();
                // deb($register);
                $id = $register->getId();
                ProcessResult::setSuccess($msg);
                // AppHeaderLocation("/dev/repository/$repository/$id/view");
                AppHeaderLocation("/dev/repository/$repository");
                exit();
            }
        });

        // ..................................................................
        $route->get('/dev/repository/sheet/:repository', function ($repository) {
            self::PrivativeDevZone();
            ViewRepository::repository_sheet_view($repository);
        });
    }
}

?>