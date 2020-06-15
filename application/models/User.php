<?php
namespace application\models;

use manguto\cms7\libraries\Exception;
use manguto\cms7\model\Model;
use manguto\cms7\model\ModelAttribute;
use manguto\cms7\model\ModelStart;
use manguto\cms7\database\repository\ModelRepository;
use manguto\cms7\database\ModelDatabase;
use manguto\cms7\libraries\ProcessResult;
use manguto\cms7\libraries\Sessions;
use manguto\cms7\libraries\Numbers;
use application\core\Access;

class User extends Model implements ModelDatabase
{

    use ModelStart;
    use ModelRepository;

    const default = [
        [
            'name' => 'Desenvolvedor',
            'login' => 'dev',
            'password' => 'ved',
            'email' => 'dev@sis.com',
            'phone' => '(XX) X.XXXX-XXXX'
        ],
        [
            'name' => 'Administrador',
            'login' => 'adm',
            'password' => 'mad', // 7538ebc37ad0917853e044b9b42bd8a4
            'email' => 'adm@sis.com',
            'phone' => '(XX) X.XXXX-XXXX'
        ],
        [
            'name' => 'Usuário',
            'login' => 'user',
            'password' => 'user',
            'email' => 'user@sis.com',
            'phone' => '(XX) X.XXXX-XXXX'
        ]
    ];

    const SESSION = "User";

    const FORGOT_EMAIL = "UserEmail";

    /**
     * !IMPORTANT
     * Função para definicao do atributos do modelo!
     */
    private function defineAttributes()
    {
        // ---------------------------------------------------
        $a = new ModelAttribute('name');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('login');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('password');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('email');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('phone');
        $this->SetAttribute($a);
        // ---------------------------------------------------
    }

    // ############################################################################################################################################

    /**
     * definicao do password (c/ cifragem)
     * @param string $value
     */
    public function setPassword(string $value)
    {
        if (trim($value) == '') {
            throw new Exception("A senha de usuário não pode ser vazia.");
        } else {
            // crypt?
            if (Numbers::is_valid_md5($value) == false) {
                $value = User::password_crypt($value);
            }
            parent::setPassword($value);
        }
    }

    // ############################################################################################################################################

    /**
     * verifica se existe algum usuario com o login / password informados
     * retornando-o caso afirmativo e caso contrario 'false'
     *
     * @param string $login
     * @param string $password
     * @throws Exception
     * @return boolean|mixed
     */
    static function checkUserCredentials(string $login, string $password)
    {
        $return = false;
        // usuario existe?
        $login__search = (new User())->search(" \$login=='$login' ");
        $n = count($login__search);
        if ($n == 1) {
            // usuario e senha correta?
            $login_and_password__search = (new User())->search(" \$login=='$login' && \$password=='" . User::password_crypt($password) . "' ");
            $m = count($login_and_password__search);
            if ($m == 1) {
                $return = array_shift($login_and_password__search);
            } else if ($m > 1) {
                throw new Exception("Foram encontrados mais de um usuário com o mesmo login e senha. Contate o administrador.");
            }
        } else if ($n > 1) {
            throw new Exception("Foram encontrados mais de um usuários com o mesmo login. Contate o administrador.");
        }
        return $return;
    }

    // ############################################################################################################################################
    /**
     * retorna um array com os perfis (objetos) do usuario
     *
     * @return array
     */
    public function getProfiles(): array
    {
        return User_profile::getUserProfiles($this->getId());
    }
  

    // ############################################################################################################################################
    /**
     * verifica se o usuario possui o perfil correspondente aa chave informada
     *
     * @param string $profileNickname
     * @return bool
     */
    public function checkProfile(string $profileNickname): bool
    {
        $profiles = $this->getProfiles();
        foreach ($profiles as $profile) {
            if ($profile->checkNickname($profileNickname, false)) {
                return true;
            }
        }
        return false;
    }

    // ############################################################################################################################################
    static function checkUpdate($POST)
    {
        // deb($_POST);
        // ================================================================== parametros
        { // alteracao de senha
            { // limpeza de tentativa de alteracao indevida da senha
                if (isset($_POST['password'])) {
                    unset($_POST['password']);
                }
            }
            { // verificacao de alteracao de senha
                $password_change = POST('password_change', false);
            }
        }
        // =============================================================================

        try {
            $user = Access::getSessionUser();
            $user->SET_DATA($POST);
            { // fields check
                $user->verifyFieldsToCreateUpdate();
            }
            { // password check
                if ($password_change !== false) {
                    { // parameters
                        $password_actual = POST('password_actual', false, false);
                        $password_new = POST('password_new', false, false);
                        $password_confirmation = POST('password_confirmation', false, false);
                    }
                    $user->verifyPasswordUpdate($password_actual, $password_new, $password_confirmation);
                }
            }
            // deb($user);
            $user->save();            
            ProcessResult::setSuccess('Alteração de dados realizada com sucesso!');
            $return = true;
        } catch (Exception $e) {
            ProcessResult::setError($e);
            Sessions::set('ControlBadge', $user);
            $return = false;
        }
        return $return;
    }

    // ############################################################################################################################################

    /**
     * Verifica se existe algum usuario com o login informado.
     * Caso haja alguma excecao, basta informar o 'id' do usuario
     * no parametro correspondente.
     *
     * @param string $login
     * @param string $exception__user_id
     * @throws Exception
     * @return bool
     */
    static function checkLoginExist(string $login, string $exception__user_id = ''): bool
    {
        { // query
            {
                if (trim($exception__user_id) != '') {
                    $exception__user_id = " && \$id!=" . intval($exception__user_id) . " ";
                }
                $query = " \$login=='$login' " . $exception__user_id;
            }
        }
        $result = (new User())->search($query);
        $n = sizeof($result);
        if ($n == 1) {
            return true;
        } else if ($n == 0) {
            return false;
        } else {
            throw new Exception("Foram encontrados $n usuários com o mesmo login. Contate o administrador.");
        }
    }

    // ############################################################################################################################################

    /**
     * Verifica se existe algum usuario com o e-mail informado.
     * Caso haja alguma excecao, basta informar o 'id' do usuario
     * no parametro correspondente.
     *
     * @param string $email
     * @param boolean $exception__user_id
     * @throws Exception
     * @return mixed|boolean
     */
    static function checkEmailExist(string $email, $exception__user_id = false)
    {
        { // query
            if ($exception__user_id == false) {
                $query = " \$email=='$email' ";
            } else {
                $query = " \$email=='$email' && \$id!=$exception__user_id ";
            }
        }
        $result = (new User())->search($query);
        $n = sizeof($result);
        if ($n == 1) {
            return array_shift($result);
        } else if ($n == 0) {
            return false;
        } else {
            throw new Exception("Foram encontrados $n usuários com o mesmo e-mail ('$email'). Contate o administrador.");
        }
    }

    // ############################################################################################################################################

    /**
     * Verifica se os campos informados ($_POST) podem ser utilizados em um usuario para criacao ou atualizacao
     *
     * @throws Exception
     */
    public function verifyFieldsToCreateUpdate()
    {

        // name
        if ($this->getname() == '') {
            throw new Exception("Preencha o nome e tente novamente.");
        }

        { // login
            $login = trim($this->getLogin());
            $id = $this->getId();
            if ($login == '') {
                throw new Exception("Preencha o login.");
            }
            if (User::checkLoginExist($login, $id)) {
                throw new Exception("O login '$login' já se encontra em uso. Preencha outro valor e tente novamente.");
            }
        }

        { // email
            $email = trim($this->getEmail());
            $id = $this->getId();
            if ($email == '') {
                throw new Exception("Preencha o e-mail.");
            }
            if (User::checkEmailExist($email, $id)) {
                throw new Exception("O e-mail '$email' já se encontra em uso. Preencha outro valor e tente novamente.");
            }
        }

        // password
        if ($this->getPassword() == '') {
            throw new Exception("Preencha a senha e tente novamente.");
        }
    }

    // ############################################################################################################################################
    private function verifyPasswordUpdate(string $current_pass, string $new_pass, string $new_pass_confirm)
    {
        $error_array = [];

        { // --- ERROR VERIFICATION
            if ($current_pass === '') {
                $error_array[] = 'Digite a senha atual.';
            }

            if (User::password_crypt($current_pass) !== $this->getPassword()) {
                $error_array[] = 'A senha atual não está correta.';
            }

            if ($new_pass === '') {
                $error_array[] = 'A nova senha não pode ser vazia.';
            }

            if ($new_pass_confirm === '') {
                $error_array[] = 'A confirmação da nova senha não pode ser vazia.';
            }

            if ($new_pass !== $new_pass_confirm) {
                $error_array[] = 'A confirmação da nova senha não confere.';
            }

            if (User::password_crypt($new_pass) === $this->getPassword()) {
                $error_array[] = 'A sua nova senha não pode ser igual a senha atual.';
            }
        }

        { // avaliacao
            if (sizeof($error_array) > 0) {
                throw new Exception(implode('<br/>', $error_array));
            } else {
                $this->setPassword($new_pass);
            }
        }
    }

    // ############################################################################################################################################
    // ############################################################################################################################################
    // ####################################################################################################################################### AUX
    // ############################################################################################################################################
    // ############################################################################################################################################
    /**
     * realiza a cifragem da string informada
     *
     * @param string $passwordRaw
     * @return string
     */
    static function password_crypt(string $passwordRaw): string
    {
        // return password_hash($passwordRaw,PASSWORD_DEFAULT,["cost"=>12]);
        return md5($passwordRaw);
    }

    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
    public function __toString()
    {
        $return = "
        <ul class='list list-striped list-bordered-bottom'>
            <li>Nome: <b>{$this->getName()}</b></li>
            <li>Login: <b>{$this->getLogin()}</b></li>
            <li>E-mail: <b>{$this->getEmail()}</b></li>
            <li>Telefone(s): <b>{$this->getPhone()}</b></li>
            <li>" . (sizeof($this->getProfiles()) == 1 ? 'Perfil' : 'Perfís') . ": <b>".implode(', ',$this->getProfilesStr())."</b></li>
        </ul>";
        return $return;
    }
}



