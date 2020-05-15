<?php
namespace application\models;

use manguto\cms7\model\Model;
use manguto\cms7\database\ModelDatabase;
use manguto\cms7\database\repository\ModelRepository;
use manguto\cms7\model\ModelAttribute;
use manguto\cms7\model\ModelStart;
use manguto\cms7\libraries\Sessions;

class User_module extends Model implements ModelDatabase
{
    
    use ModelStart;
    use ModelRepository;

    /**
     * Função para definicao do atributos do modelo (ModelAttribute's)
     */
    private function defineAttributes()
    {
        // -------------------------------------------------
        $a = new ModelAttribute('user_id');
        $a->setType(ModelAttribute::TYPE_INT);
        $this->SetAttribute($a);
        // -------------------------------------------------
        $a = new ModelAttribute('module');
        $this->SetAttribute($a);
        // -------------------------------------------------
        $a = new ModelAttribute('nature');
        $this->SetAttribute($a);
        // -------------------------------------------------
    }

    /**
     * obtem os modulos acessiveis pelo usuario informado
     *
     * @param string|int $user_id
     * @return array
     */
    static function getUserModules($user_id): array
    {
        $user_modules = (new User_module())->search(" \$user_id==$user_id ");
        $return = [];
        foreach ($user_modules as $user_module) {
            $return[] = $user_module->getModule();
        }
        { // adicionar o proprio sistema em questao
            $return[] = APP_FOLDERNAME;
        }
        return $return;
    }

    /**
     * obtem os links (HTML) para os modulos
     *
     * @return array
     */
    static function getUserModulesHomeMenu(): array
    {
        $return = [];
        if (Sessions::isset(User::SESSION . '_modules')) {
            foreach (Sessions::get(User::SESSION . '_modules') as $module) {
                if ($module != APP_FOLDERNAME) {
                    $return[] = [
                        'href' => "http://{$_SERVER['HTTP_HOST']}/" . $module,
                        'html' => strtoupper($module)
                    ];
                }
            }
        }
        return $return;
    }

    /**
     * atualiza o perfil do usuario
     * para acesso dos outros modulos
     * disponiveis
     *
     * @param string $action
     * @param string $key
     */
    static function externalUserModuleUpdate(string $action, string $key)
    {
        // ===================================================================
        { // parseamento da chave
            $key_array = explode('___', $key);
            $user_id = $key_array[0];
            $module = $key_array[1];
            $nature = $key_array[2];
        }
        // ===================================================================
        { // remocao de todos os perfis do usuario para um determinado modulo
            $query = " \$user_id==$user_id && \$module=='$module' ";
            $user_module_array = (new User_module())->search($query);
            if (sizeof($user_module_array) > 0) {
                foreach ($user_module_array as $user_module) {
                    $user_module->delete();
                }
            }
        }
        // ===================================================================
        { // insere o novo perfil do usuario para o modulo em questao caso seja uma definicao (action='set')
            if ($action == 'set') {
                $user_module = new User_module();
                $user_module->setUser_id($user_id);
                $user_module->setModule($module);
                $user_module->setNature($nature);
                $user_module->save();
                //deb($user_module);
            }
        }
    }
}

?>