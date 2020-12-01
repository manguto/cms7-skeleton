<?php
namespace application\models;

use manguto\cms7\model\Model;
use manguto\cms7\database\ModelDatabase;
use manguto\cms7\database\repository\ModelRepository;
use manguto\cms7\model\ModelAttribute;
use manguto\cms7\model\ModelSetup;
use manguto\cms7\libraries\Exception;

class User_profile extends Model implements ModelDatabase
{
    
    use ModelSetup;
    use ModelRepository;

    const default = [
        [
            'user_id' => '1',
            'profile_id' => '1'
        ],
        [
            'user_id' => '2',
            'profile_id' => '2'
        ],
        [
            'user_id' => '3',
            'profile_id' => '3'
        ],
    ];

    /**
     * Função para definicao do atributos do modelo (ModelAttribute's)
     */
    private function defineAttributes()
    {
        // ---------------------------------------------------
        $a = new ModelAttribute('user_id');
        $a->setType(ModelAttribute::TYPE_INT);
        $a->setNature(ModelAttribute::NATURE_REFERENCE_SINGLE);
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('profile_id');
        $a->setType(ModelAttribute::TYPE_INT);
        $a->setNature(ModelAttribute::NATURE_REFERENCE_SINGLE);
        $this->SetAttribute($a);
        // ---------------------------------------------------
    }
    
    /**
     * obtem os perfis do usuario informado
     * @param int|string $user_id
     * @return array
     */
    static function getUserProfiles($user_id):array{
        $return = [];
        $user_id = intval($user_id);
        $user_profiles = (new User_profile())->search(" \$user_id==$user_id ");
        //deb($user_profiles);
        if(count($user_profiles)>0){
            foreach ($user_profiles as $user_profile){
                $profile_id = $user_profile->getProfile_id();
                $return[$profile_id] = new Profile($profile_id);
            }
        }
        return $return;
    }
}




?>