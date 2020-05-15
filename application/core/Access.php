<?php
namespace application\core;

use manguto\cms7\libraries\Sessions;
use application\models\User; 

class Access
{    
    
    public function __construct() {
        

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
            self::setSessionUser($user);
            return true;
        }
        return false;
    }
    
    // ############################################################################################################################################
    
    /**
     * verifica se existe um usuario logado no sistema
     *
     * @return bool
     */
    static function checkUserLogged(): bool
    {
        return Sessions::isset(User::SESSION);
    }
    
    // ############################################################################################################################################
    static function checkUserLoggedAdmin(): bool
    {
        if (self::checkUserLogged()) {
            $user = self::getSessionUser();
            $return = $user->checkProfile('admin',false);
        } else {
            $return = false;
        }
        return $return;
    }
    
    // ############################################################################################################################################
    static function checkUserLoggedDev(): bool
    {
        if (self::checkUserLogged()) {
            $user = self::getSessionUser();
            $return = $user->checkProfile('dev',false);
        } else {
            $return = false;
        }
        return $return;
    }
    
    // ############################################################################################################################################
    static function setSessionUser(User $user)
    {
        Sessions::set(User::SESSION, $user);        
    }
    
    // ############################################################################################################################################
    static function getSessionUser()
    {
        return Sessions::get(User::SESSION, false);
    }
    
    // ############################################################################################################################################
    static function getSessionUserAttribute($attribute)
    {
        $user = self::getSessionUser();
        if ($user !== false) {
            $getMethod = 'get' . ucfirst($attribute);
            $attribute = $user->$getMethod();
        } else {
            $attribute = '';
        }
        return $attribute;
    }
    
    // ############################################################################################################################################
    static function clearSessionUser()
    {
        Sessions::unset(User::SESSION);
    }
   
    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################
    // ############################################################################################################################################

}