<?php
namespace application\models;

use manguto\cms7\model\Model;
use manguto\cms7\database\ModelDatabase;
use manguto\cms7\database\repository\ModelRepository;
use manguto\cms7\model\ModelAttribute;
use manguto\cms7\model\ModelStart;
use manguto\cms7\libraries\Exception;

class Profile extends Model implements ModelDatabase
{

    use ModelStart;
    use ModelRepository;

    const default = [
        [
            'name' => 'Desenvolvedor',
            'nickname' => 'dev',
            'obs' => ''
        ],
        [
            'name' => 'Administrador',
            'nickname' => 'admin',
            'obs' => ''
        ],
        [
            'name' => 'Usuário',
            'nickname' => 'user',
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

    /**
     * verifica se a 'nickname' deste perfil eh igual a informada
     *
     * @param string $nickname
     * @param bool $throwException
     * @throws Exception
     * @return boolean
     */
    public function checkNickname(string $nickname, bool $throwException = false): bool
    {
        if (Profile::checkNicknameExist($nickname, $throwException) && $this->getNickname() == trim($nickname)) {
            $return = true;
        } else {
            if ($throwException) {
                throw new Exception("A chave de Perfil informada ('$nickname') não foi encontrada no sistema.");
            } else {
                $return = false;
            }
        }
        return $return;
    }
    
    
    public function __toString()
    {
        return $this->getName();
    }
}

?>