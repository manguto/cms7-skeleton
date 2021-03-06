<?php
namespace application\controllers\devend;

use manguto\cms7\application\core\Access;
use manguto\cms7\libraries\Alert;
use application\views\devend\ViewRepository;
use manguto\cms7\libraries\Exception;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;

class ControllerRepository extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/repository', function () {
            Access::CheckUserProfiles([
                "dev"
            ]);
            ViewRepository::repository();
        });

        $route->get('/dev/repository/:repository', function ($repository) {
            Access::CheckUserProfiles([
                "dev"
            ]);
            ViewRepository::repository_view($repository);
        });

        $route->get('/dev/repository/:repository/:id/view', function ($repository, $id) {
            Access::CheckUserProfiles([
                "dev"
            ]);
            // deb($repository,0); deb($id);
            {
                // $repositoryNameCall = Repository::getObjectClassname($repository);
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
            Access::CheckUserProfiles([
                "dev"
            ]);
            // deb($repository,0); deb($id);
            {
                // $repositoryNameCall = Repository::getObjectClassname($repository);
                throw new Exception("");

                // deb($repositoryNameCall);
                $register = new $repositoryNameCall($id);
                // deb($register);
                $register->delete();
                Alert::Success("Registro removido com sucesso.");
                Controller::HeaderLocation("/dev/repository/$repository");
                exit();
            }
        });

        $route->get('/dev/repository/:repository/:id/edit', function ($repository, $id) {
            Access::CheckUserProfiles([
                "dev"
            ]);
            // deb($repository,0); deb($id);
            {
                // $repositoryNameCall = Repository::getObjectClassname($repository);
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
            Access::CheckUserProfiles([
                "dev"
            ]);
            // deb($repository,0); deb($id);
            // deb($_POST);
            {
                // $repositoryNameCall = Repository::getObjectClassname($repository);
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
                Alert::Success($msg);
                // Controller::HeaderLocation("/dev/repository/$repository/$id/view");
                Controller::HeaderLocation("/dev/repository/$repository");
                exit();
            }
        });

        // ..................................................................
        $route->get('/dev/repository/sheet/:repository', function ($repository) {
            Access::CheckUserProfiles([
                "dev"
            ]);
            ViewRepository::repository_sheet_view($repository);
        });
    }
}

?>