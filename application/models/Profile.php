<?php
namespace application\models;

use manguto\cms7\model\Model;
use manguto\cms7\database\ModelDatabase;
use manguto\cms7\database\repository\ModelRepository;
use manguto\cms7\model\ModelAttribute;
use manguto\cms7\model\ModelSetup;
use manguto\cms7\libraries\Exception;

class Profile extends Model implements ModelDatabase
{

    use ModelSetup;
    use ModelRepository;

    const default = [
        [
            'name' => 'Desenvolvedor',
            'nickname' => 'dev',
            'level' => '1',
            'obs' => ''
        ],
        [
            'name' => 'Administrador',
            'nickname' => 'admin',
            'level' => '2',
            'obs' => ''
        ],
        [
            'name' => 'Usuário',
            'nickname' => 'user',
            'level' => '3',
            'obs' => ''
        ]
    ];

    /**
     * Função para definicao do atributos do modelo (ModelAttribute's)
     */
    private function defineAttributes()
    {
        // ---------------------------------------------------
        $a = new ModelAttribute('name');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('nickname');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('level');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('obs');
        $this->SetAttribute($a);
        // ---------------------------------------------------
    }

    /**
     * verifica se a 'nickname' informada existe dentre as especificadas no sistema
     *
     * @param string $nickname
     * @param bool $throwException
     * @throws Exception
     * @return bool
     */
    static function checkNicknameExist(string $nickname, bool $throwException = false): bool
    {
        foreach (Profile::default as $info) {
            $nickname_temp = $info['nickname'];
            if ($nickname == trim($nickname_temp)) {
                return true;
            }
        }
        if ($throwException) {
            throw new Exception("Não foi encontrado nenhum perfil com o 'apelido' informado ($nickname).");
        } else {
            return false;
        }
    }

    
    public function __toString()
    {
        return $this->getName();
    }
}

?>