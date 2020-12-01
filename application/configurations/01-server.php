<?php
use manguto\cms7\libraries\ServerHelp;
// ####################################################################################################
{
    // em desenvolvimento?
    define('DEVELOPMENT', false);
}
// ####################################################################################################
{
    // tratamento de eventos (erros, notices, exceptions...)
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}
// ####################################################################################################
{
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Recife');
}
// ####################################################################################################
{
    /**
     * quantidade de niveis acima da pasta deste arquivo para a pasta raiz
     *
     * @var int
     */
    define('LEVELS_TO_ROOT_FOLDER', 2);
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
    define('APP_CWD', getcwd() . DS);
    // deb(APP_CWD);
}
// ####################################################################################################
{
    {
        $SERVER_DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
        $SERVER_DOCUMENT_ROOT = str_replace('/', DS, $SERVER_DOCUMENT_ROOT);
        $SERVER_DOCUMENT_ROOT = str_replace('\\', DS, $SERVER_DOCUMENT_ROOT);
    }
    define("APP_SERVER_ROOT_DIR", $SERVER_DOCUMENT_ROOT);
    // deb(APP_SERVER_ROOT_DIR);
}

// ####################################################################################################
{
    /**
     * caminho para a raiz do app
     *
     * @var string
     */
    define('APP_PATH', dirname(__DIR__, LEVELS_TO_ROOT_FOLDER) . DS);
}
// ####################################################################################################
{
    /**
     * nome da pasta do aplicativo
     *
     * @var string
     */
    define('APP_FOLDER', basename(APP_PATH));
    // deb(APP_FOLDER);
}
// ####################################################################################################
{
    /**
     * caminho relativo do dominio aa raiz do aplicativo
     *
     * @var string
     */
    define("APP_RELATIVE_PATH", str_replace(APP_SERVER_ROOT_DIR, '', APP_PATH));
}

// ####################################################################################################
{
    /**
     * caminho relativo do arquivo principal (index.php) até a raiz da aplicacao
     *
     * @var string
     */
    define("APP_ROOT_PATH", '');
}
// ####################################################################################################
{
    /**
     * identificador unico no dominio para o aplicativo (baseado no caminho relativo do dominio ao diretorio do app)
     *
     * @var string
     */    
    define("APP_BASENAME", trim(ServerHelp::fixds(APP_RELATIVE_PATH,' ')));
    //deb(APP_BASENAME);
}
// ####################################################################################################
{    
    /**
     * caminho base para insercao na url a partir do dominio
     *
     * @var string
     */    
    define("APP_URL_ROOT", APP_VIRTUAL_HOST ? '' : ServerHelp::fixds(APP_RELATIVE_PATH, '/'));
    //deb(APP_URL_ROOT);
}
// ####################################################################################################
{   
    /**
     * endereco do aplicativo
     *
     * @var string
     */
    define("APP_URL_HOST", 'http://'.ServerHelp::fixds( $_SERVER['HTTP_HOST'] . APP_URL_ROOT,'/'));
    //deb(APP_URL_HOST);
}
// ####################################################################################################
{
    /**
     * timestamp no momento do ciclo (carregamento)
     * @var int
     */
    define("APP_TIMESTAMP", time());    
}
// ####################################################################################################
{
    // 
    /**
     * identificador do ciclo (cada processamento do index.php)
     * @var
     */
    define("APP_ITERATION", uniqid());
}
// ####################################################################################################

?>