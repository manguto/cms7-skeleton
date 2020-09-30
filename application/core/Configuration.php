<?php
namespace application\core;

use manguto\cms7\libraries\Diretorios;
use manguto\cms7\libraries\ServerHelp;
use manguto\cms7\libraries\Strings;
use manguto\cms7\libraries\Numbers;
use manguto\cms7\libraries\Sessions;
use manguto\cms7\libraries\Logger;

class Configuration
{

    const configuration_dir = 'application/configurations/';

    const app_configuration_filename = 'config.php';

    /**
     * inicializacao da configuracoes
     */
    public function __construct()
    {
        Logger::info('Configuração inicializacao');
        
        $this->loadConfigurations();

        $this->automaticConstants();
    }

    /**
     * carregamento das configuracoes da aplicacao
     */
    private function loadConfigurations()
    {
        { // carregamento dos arquivos internos da aplicacao
            // obtem os arquivos configuracionais e os ordena
            $configFilenameArray = Diretorios::obterArquivosPastas(ServerHelp::fixds(self::configuration_dir), true, true, false, [
                'php'
            ]);
            // ordena os arquivos conforme as numeracoes dos arquivos
            sort($configFilenameArray);
            // carregamento dos arquivos de conf

            foreach ($configFilenameArray as $configFilename) {
                require_once $configFilename;
            }
        }
        { // carregamento do arquivo de configuracao da aplicacao
            require_once ServerHelp::fixds(self::app_configuration_filename);
        }
    }

    /**
     * define constantes automaticas
     */
    private function automaticConstants()
    {
        // ####################################################################################################
        { // plataforma atual
            $url = ServerHelp::getURL();
            $url = Route::fixURL($url);
            // deb($url);
            if (Strings::checkIni('admin/', $url)) {
                $platform = 'backend';
            } else if (Strings::checkIni('dev/', $url)) {
                $platform = 'development';
            } else {
                $platform = 'frontend';
            }
            // deb($platform);
            define('APP_PLATFORM', $platform);
        }
        // ####################################################################################################
        { // ip do usuario
            define('APP_USER_IP', ServerHelp::getIp());
        }
        // ####################################################################################################
        { // ip do usuario mascarado
            {
                $APP_USER_IP_MASKED = explode('.', APP_USER_IP);
                $APP_USER_IP_MASKED = array_map(function ($value) {
                    return Numbers::str_pad_left(strval($value), 3);
                }, $APP_USER_IP_MASKED);
                $APP_USER_IP_MASKED = implode('_', $APP_USER_IP_MASKED);
                // deb($APP_USER_IP_MASKED);
            }
            define('APP_USER_IP_MASKED', $APP_USER_IP_MASKED);
        }
        // ####################################################################################################
        { // iteracao
            {
                $APP_ITERATION = Sessions::get('APP_ITERATION', false, true);
                if ($APP_ITERATION === false) {
                    $APP_ITERATION = date('Ymd-His', APP_TIMESTAMP) . '-' . APP_UNIQID;
                }
            }
            define("APP_ITERATION", $APP_ITERATION);
        }
        // ####################################################################################################
        // ####################################################################################################
        // ####################################################################################################
        { // DEBUG CONSTANTS!
            /*
             * foreach (get_defined_constants() as $cteName => $cteValue) {
             * if (strpos($cteName, 'APP_') !== false) {
             * echo "<b>$cteName</b>";
             * deb($cteValue, 0);
             * echo "<hr/>";
             * }
             * }
             * die('');/*
             */
        }
    }
}

?>



