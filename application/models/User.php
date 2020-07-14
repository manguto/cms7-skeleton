<?php
namespace application\models;

use manguto\cms7\libraries\Exception;
use manguto\cms7\model\Model;
use manguto\cms7\model\ModelAttribute;
use manguto\cms7\model\ModelSetup;
use manguto\cms7\database\repository\ModelRepository;
use manguto\cms7\database\ModelDatabase;
use manguto\cms7\libraries\Alert;
use manguto\cms7\libraries\Sessions;
use manguto\cms7\libraries\Numbers;
use application\core\Access;
use manguto\cms7\libraries\Logger;
use manguto\cms7\libraries\Variables;

class User extends Model implements ModelDatabase
{

    use ModelSetup;
    use ModelRepository;

    const default = [
        [
            'name' => 'Desenvolvedor',
            'password' => '29e0461b02c078c89c7b2ac0b29fbfaf', //reflex
            'email' => 'dev',
            'phone' => '(XX) X.XXXX-XXXX'
        ],
        [
            'name' => 'Administrador',
            'password' => 'ee10c315eba2c75b403ea99136f5b48d', //mirror
            'email' => 'admin',
            'phone' => '(XX) X.XXXX-XXXX'
        ],
        [
            'name' => 'Usuário',
            'password' => 'ee11cbb19052e40b07aac0ca060c23ee', //user
            'email' => 'user',
            'phone' => '(XX) X.XXXX-XXXX'
        ]
    ];

    const SESSION = "User";

    const FORGOT_EMAIL = "UserEmail";
    
    private function defineAttributes()
    {
        // ---------------------------------------------------
        $a = new ModelAttribute('name');
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
        $value = trim($value);
        if ($value == '') {
            throw new Exception("A senha de usuário não pode ser vazia.");
        } else {
            // cifra apenas caso jah nao esteja cifrado (formato md5)?
            if (!Numbers::is_valid_md5($value)) {
                $value = User::password_crypt($value);
            }
            parent::setPassword($value);
        }
    }

    // ############################################################################################################################################

    /**
     * verifica se existe algum usuario com o email / password informados
     * retornando-o caso afirmativo e caso contrario 'false'
     *
     * @param string $email
     * @param string $password
     * @throws Exception
     * @return boolean|User
     */
    static function checkUserCredentials(string $email, string $password)
    {
        {
            // existe algum usuario com o e-mail informado?        
            $email__search = (new User())->search(" \$email=='$email' ");        
            $n = count($email__search);
            
        }
        
        if ($n == 1) {
            Logger::info("Foi encontrado $n usuário com o e-mail '$email'.");
            // usuario e senha correta?
            $email_and_password__search = (new User())->search(" \$email=='$email' && \$password=='" . User::password_crypt($password) . "' ");
            $m = count($email_and_password__search);
            if ($m == 1) {
                $return = array_shift($email_and_password__search);
            } else if ($m > 1) {
                throw new Exception("Foram encontrados mais de um usuário com o mesmo email e senha. Contate o administrador.");
            } else {
                Logger::error("A senha informada para o e-mail '$email' não procede. Acesso negado!");
                $return = false;
            }
        } else if ($n > 1) {
            throw new Exception("Foram encontrados mais de um usuários com o mesmo email. Contate o administrador.");
        } else {
            Logger::error("Nenhum usuário encontrado com o e-mail '$email'.");
            $return = false;
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
        $user_id = $this->getId();
        //deb($user_id);
        $return = User_profile::getUserProfiles($user_id);
        //deb($return,0);
        Logger::info("Perfil(is) do usuario atual (".implode(', ', $return).") => ");
        return $return;
    }
  

    // ############################################################################################################################################
    /**
     * verifica se o usuario possui o perfil correspondente aa chave informada
     *
     * @param string $profileNickname
     * @return bool
     */
    public function checkProfile(string $wantedProfileNickname): bool
    {
        $wantedProfile_array = (new Profile())->search(" \$nickname=='$wantedProfileNickname' ");
        //deb($wantedProfile);
        if(sizeof($wantedProfile_array)==0){
            throw new Exception("Não foi encontrado nenhum perfil como termo informado: '$wantedProfileNickname'.");
        }
        $wantedProfile = array_shift($wantedProfile_array);
        $wantedProfileLevel = $wantedProfile->getLevel();
        //deb($wantedProfileLevel);
        Logger::info("Perfil alvo => nickname:'$wantedProfileNickname' | level:'$wantedProfileLevel'.");        
        $profileTemp_array = $this->getProfiles();
        Logger::info("Quantidade de Perfís do usuario: ".sizeof($profileTemp_array));
        foreach ($profileTemp_array as $profileTemp) {            
            {
                $profileTempNickname = $profileTemp->getNickname();
                $profileTempLevel = $profileTemp->getLevel();
            }
            
            Logger::info("Perfil (temp) => nickname:'{$profileTemp->getNickname()}' | level:'$profileTempLevel'.");
            
            if ($wantedProfileNickname == $profileTempNickname)  {
                Logger::success("O usuário atual possui o perfil solicitado ('$wantedProfileNickname').");
                return true;
            }
            
            if($profileTempLevel <= $wantedProfileLevel){                
                Logger::success("O perfil atual em análise do usuário, possui NÍVEL MENOR OU IGUAL ao do perfil solicitado ($profileTempLevel <= $wantedProfileLevel).");
                return true;
            }
            
            Logger::warning("O perfil atual em análise do usuário, NÃO possui NÍVEL MENOR OU IGUAL ao do perfil solicitado ($profileTempLevel > $wantedProfileLevel).");
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
                $password_change = $_POST['password_change'] ?? false;
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
                        $password_actual = Variables::POST('password_actual', false, false);
                        $password_new = Variables::POST('password_new', false, false);
                        $password_confirmation = Variables::POST('password_confirmation', false, false);
                    }
                    $user->verifyPasswordUpdate($password_actual, $password_new, $password_confirmation);
                }
            }
            // deb($user);
            $user->save();            
            Alert::setSuccess('Alteração de dados realizada com sucesso!');
            $return = true;
        } catch (Exception $e) {
            //deb($e);
            Alert::setDanger($e);
            Sessions::set('ControlBadge', $user);
            $return = false;
        }
        return $return;
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
                $error_array[] = 'Digite a senha atual. Tente novamente!';
            }

            if (User::password_crypt($current_pass) !== $this->getPassword()) {
                $error_array[] = 'A senha atual não está correta. Tente novamente!';
            }

            if ($new_pass === '') {
                $error_array[] = 'A nova senha não pode ser vazia. Tente novamente!';
            }

            if ($new_pass_confirm === '') {
                $error_array[] = 'A confirmação da nova senha não pode ser vazia. Tente novamente!';
            }

            if ($new_pass !== $new_pass_confirm) {
                $error_array[] = 'A confirmação da nova senha não confere. Tente novamente!';
            }

            if (User::password_crypt($new_pass) === $this->getPassword()) {
                $error_array[] = 'A sua nova senha não pode ser igual a senha atual. Tente novamente!';
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
        {
            $profiles = $this->getProfiles();
            if(sizeof($profiles)==1){
                $profile = array_shift($profiles);
                $profileTitle = 'Perfil:';
                $profiles = "$profile";
            }else{
                $profileTitle = 'Perfís:';
                $profiles = [];
                foreach ($profiles as $profile) $profiles[]="$profile";
                $profiles=implode(', ', $profiles);
            }
        }
        $return = "
        <ul class='list list-striped list-bordered-bottom'>
            <li>Nome: <b>{$this->getName()}</b></li>            
            <li>E-mail: <b>{$this->getEmail()}</b></li>
            <li>Telefone(s): <b>{$this->getPhone()}</b></li>
            <li>$profileTitle <b>$profiles</b></li>
        </ul>";
        return $return;
    }
}



