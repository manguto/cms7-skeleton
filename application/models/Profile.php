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
            'key' => 'dev',
            'obs' => ''
        ],
        [
            'name' => 'Administrador',
            'key' => 'admin',
            'obs' => ''
        ],
        [
            'name' => 'Usuário',
            'key' => 'user',
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
        $a = new ModelAttribute('key');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('obs');
        $this->SetAttribute($a);
        // ---------------------------------------------------
    }

    /**
     * verifica se a 'key' informada existe dentre as especificadas no sistema
     *
     * @param string $key
     * @param bool $throwException
     * @throws Exception
     * @return bool
     */
    static function checkKeyExist(string $key, bool $throwException = false): bool
    {
        foreach (Profile::default as $info) {
            $key_temp = $info['key'];
            if ($key == trim($key_temp)) {
                return true;
            }
        }
        if ($throwException) {
            throw new Exception("Não foi encontrado nenhum perfil com a 'chave' informada ($key).");
        } else {
            return false;
        }
    }

    /**
     * verifica se a 'key' deste perfil eh igual a informada
     *
     * @param string $key
     * @param bool $throwException
     * @throws Exception
     * @return boolean
     */
    public function checkKey(string $key, bool $throwException = false): bool
    {
        if (Profile::checkKeyExist($key, $throwException) && $this->getKey() == trim($key)) {
            $return = true;
        } else {
            if ($throwException) {
                throw new Exception("A chave de Perfil informada ('$key') não foi encontrada no sistema.");
            } else {
                $return = false;
            }
        }
        return $return;
    }
}

?>