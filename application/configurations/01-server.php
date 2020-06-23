<?php
use manguto\cms7\libraries\ServerHelp;
// ####################################################################################################
{
    date_default_timezone_set('America/Recife');
}
// ####################################################################################################
{
    define("APP_VIRTUAL_HOST", false);
}
// ####################################################################################################
{
    define("DS", DIRECTORY_SEPARATOR);
}
// ####################################################################################################
{
    define('APP_CWD',getcwd().DS);
    //deb(APP_CWD);
}
// ####################################################################################################
{
    {
        $cte = $_SERVER['DOCUMENT_ROOT'];
        $cte = str_replace('/', DS, $cte);
        $cte = str_replace('\\', DS, $cte);
    }        
    define("APP_SERVER_ROOT_DIR", $cte);
    //deb(APP_SERVER_ROOT_DIR);
}

// ####################################################################################################
{  
    {
        $cte = dirname(__DIR__, 2) . DS;
        $cte = str_replace(APP_SERVER_ROOT_DIR, '', $cte);
    }
    /*
     * caminho relativo a partir do dominio
     */
    define("APP_DIR_RELATIVE", $cte);
    //deb(APP_DIR_RELATIVE);
}

// ####################################################################################################
{   
    $APP_DIRECTORY = ''; 
    //deb($APP_DIRECTORY);
    /**
     * raiz relativa da aplicacao a partir da raiz do servidor web
     *
     * @var string
     */
    define("APP_DIRECTORY", $APP_DIRECTORY);
    //deb(APP_DIRECTORY);
}
// ####################################################################################################
{
    
    {
        $cte = APP_DIR_RELATIVE;
        $cte = str_replace('/', ' ', $cte);
        $cte = str_replace('\\', ' ', $cte);
        $cte = trim($cte);
    }
    /**
     * nome base do aplicativo relativo ao caminho (diretorios) do mesmo 
     *
     * @var string
     */
    define("APP_BASENAME", $cte);
    //deb(APP_BASENAME);
}
// ####################################################################################################
{

    if (APP_VIRTUAL_HOST==true) {
        $cte = "";
    } else {
        $cte = ServerHelp::fixds(APP_DIR_RELATIVE, '/');
    }
    /**
     * caminho base para insercao na url a partir do dominio
     *
     * @var string
     */
    define("APP_URL_ROOT", $cte);
    //deb(APP_URL_ROOT);
}
// ####################################################################################################
{
    {
        $cte = 'http://'.$_SERVER['HTTP_HOST'] . APP_URL_ROOT;
        $cte = str_replace('\\', '/', $cte);
        //deb($cte);
    }
    /**
     * endereco url do aplicativo
     * @var string
     */
    define("APP_URL_HOST", $cte);
    //deb(APP_URL_HOST);
}
// ####################################################################################################
{
    define("APP_TIMESTAMP", time());
}
// ####################################################################################################
{
    define("APP_UNIQID", uniqid());
}
// ####################################################################################################

?>