<?php
namespace application\core;

use manguto\cms7\libraries\Sessions;
use application\models\User;
use manguto\cms7\libraries\Exception;
use manguto\cms7\libraries\Logger;

class Access
{

    public function __construct()
    {
        Logger::info('Controle de acesso inicializado - '.__METHOD__);
    }

    // ############################################################################################################################################
    // ############################################################################################################################ LOGIN / LOGOUT
    // ############################################################################################################################################
    /**
     * verifica se as credenciais de usuario informadas sao validas
     *
     * @param string $login
     * @param string $password
     * @return bool
     */
    static function checkUserCredentials(string $login, string $password): bool
    {
        $user = User::checkUserCredentials($login, $password);
        if ($user !== false) {
            self::setSessionUser_id($user->getId());
            return true;
        }else{
            self::resetSession();
            return false;
        }        
    }

    // ############################################################################################################################################
    /**
     * PORTEIRO - verifica se o usuario possui 
     * o perfil informado para liberacao do acesso
     * ou redireciona-o aa pagina de login. 
     * @param string $profile [admin,dev,user]
     * @return boolean
     */
    static function Concierge(string $profile){
        if(self::checkUserProfiles([$profile]) == false){
            Controller::HeaderLocation('/login');
        }else{
            return true;
        }
    }
    // ############################################################################################################################################
    static function checkUserProfiles(array $required_profile_nickname_array)
    {
        if (sizeof($required_profile_nickname_array) == 0) {
            return true;
        } else {
            if (self::checkUserLogged()) {
                foreach ($required_profile_nickname_array as $required_profile_nickname) {
                    $user = self::getSessionUser();
                    if($user->checkProfile($required_profile_nickname)===true){
                        return true;
                    }
                }
            } else {
                return false;
            }
        }
        return false;
    }

    // ############################################################################################################################################

    /**
     * verifica se existe alguum usuario logado
     *
     * @return bool
     */
    static function checkUserLogged(): bool
    {
        return Sessions::isset(User::SESSION);
    }

    // ############################################################################################################################################
    
    /**
     * verifica se o usuario atual possui o perfil (apelido) informado
     * @param string $profile_nickname
     * @return bool
     */
    static function checkUserProfile(string $profile_nickname): bool
    {
        $user = self::getSessionUser();
        return $user->checkProfile($profile_nickname, false);
    }

    // ############################################################################################################################################
    
    /**
     * define o usuario atualmente logado 
     * @param User $user
     */
    static function setSessionUser_id($user_id)
    {
        Sessions::set(User::SESSION, $user_id);
    }

    // ############################################################################################################################################
    
    /**
     * obtem o usuario logado ou levanta um excecao
     * @return User
     * @throws Exception
     */
    static function getSessionUser():User
    {
        $user_id = Sessions::get(User::SESSION,false);
        if($user_id!==false){
            $user = new User($user_id);
        }else{
            $user = false;
        }        
        return $user;
    }

    // ############################################################################################################################################
    
    /**
     * obtem um parametro do usuario atual
     * @param string $attributeName
     * @return mixed|string
     */
    static function getSessionUserAttribute(string $attributeName)
    {
        $user = self::getSessionUser();
        if ($user !== false) {
            $getMethod = 'get' . ucfirst($attributeName);
            return $user->$getMethod();
        } else {
            return 'Nenhum usuário logado';
        }        
    }

    // ############################################################################################################################################
    /*
     * remove registro de usuario logado no sistema
     */
    static function resetSession()
    {
        Sessions::Reset();        
    }

    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
}