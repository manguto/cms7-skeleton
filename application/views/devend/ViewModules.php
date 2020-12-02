<?php
namespace application\views\devend;

use manguto\cms7\application\core\View;
use application\models\User;
use application\models\User_module;
use manguto\cms7\libraries\Diretorios;
use application\models\Profile;
 
class ViewModules extends View
{

    static function modules()
    {
        
        $diretorios = Diretorios::obterArquivosPastas('..'.DIRECTORY_SEPARATOR, false, false, true);
        //deb($diretorios);
        $modules=[];
        foreach ($diretorios as $dir){
            $dir = str_replace('..'.DIRECTORY_SEPARATOR, '', $dir); 
            $dir = str_replace(DIRECTORY_SEPARATOR, '', $dir);
            $esq = APP_BASENAME.'_';
            $dir_esq = substr($dir, 0,strlen($esq));
            if($dir_esq==$esq){
                $modules[] = $dir;
            }
        }        
        $users = (new User())->search();
        {
            foreach ($users as $user){
                //if(false) $user = new User();
                
            }
        }
        // deb($users);
        
        $profiles = (new Profile())->search(' ORDER BY id ASC ');
        // deb($profiles);
        {
            $user_module_set = [];
            $user_module_array = (new User_module())->search();
            // deb($user_module_array);
            foreach ($user_module_array as $user_module) {
                $user_id = $user_module->getUser_id();
                $module = $user_module->getModule();
                $nature = $user_module->getNature();
                $user_module_set[$user_id][$module][$nature] = true;
            }
        }
        // deb($user_module_set);

        self::PageDevend('modules', get_defined_vars());
    }
}