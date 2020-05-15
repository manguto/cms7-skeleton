<?php
namespace application\controllers\devend;

use manguto\cms7\libraries\Diretorios;
use manguto\cms7\libraries\Files;
use manguto\cms7\model\ModelHelper;
use application\core\Controller; use application\core\Route; 
use application\core\View;

class Models extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        
        // ----------------------------------------------------------------------
        $route->get('/dev/models', function () {
            self::PrivativeDevZone();
            {
                $models = ModelHelper::get();
            }
            View::PageDevend('models', get_defined_vars());
        });
        // ----------------------------------------------------------------------
        $route->get('/dev/models/initialize', function () {
            // self::PrivativeDevZone();
            {
                self::Initializer();
            }
            AppHeaderLocation('/');
        });
    }

    // ##################################################################
    // ##################################################################
    // ##################################################################
    static function Initializer()
    {   
        $models = Diretorios::obterArquivosPastas(APP_CMS_MODEL_PATH, false, true, false, [
            'php'
        ]);
        // deb($models);
        foreach ($models as $model) {
            $modelClassName = Files::getBaseName($model, false);
            if ($modelClassName == 'Zzz' || substr($modelClassName, 0, 1) == '_'){
                continue;
            }   
            $modelClassNamePath = ModelHelper::getObjectClassName_by_ClassName($modelClassName);            
            $modelClassNamePath::initialize();            
        }     
    }
}

?>