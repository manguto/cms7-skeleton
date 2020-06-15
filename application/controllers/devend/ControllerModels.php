<?php
namespace application\controllers\devend;

use application\core\Access;
use manguto\cms7\libraries\Diretorios;
use manguto\cms7\libraries\Files;
use manguto\cms7\model\ModelHelper;
use application\core\Controller;
use application\core\Route;
use application\core\View;

class ControllerModels extends Controller
{

    static function RouteMatchCheck(Route $route)
    {

        // ----------------------------------------------------------------------
        $route->get('/dev/models', function () {
            Access::CheckUserProfiles([
                "developer"
            ]);
            {
                $models = ModelHelper::get();
            }
            View::PageDevend('models', get_defined_vars());
        });
        // ----------------------------------------------------------------------
        $route->get('/dev/models/initialize', function () {
            // Access::CheckUserProfiles(["developer"]);
            {
                self::Initializer();
            }
            Controller::HeaderLocation('/');
        });
    }

    // ##################################################################
    // ##################################################################
    // ##################################################################
    static function Initializer()
    {
        $models = Diretorios::obterArquivosPastas(APP_MODEL_DIR, false, true, false, [
            'php'
        ]);
        // deb($models);
        foreach ($models as $model) {
            $modelClassName = Files::getBaseName($model, false);
            if ($modelClassName == 'Zzz' || substr($modelClassName, 0, 1) == '_') {
                continue;
            }
            $modelClassNamePath = ModelHelper::getObjectClassName_by_ClassName($modelClassName);
            $modelClassNamePath::initialize();
        }
    }
}

?>