<?php
namespace application\core;

use manguto\cms7\libraries\Diretorios;
use manguto\cms7\libraries\ServerHelp;
use manguto\cms7\libraries\Strings;
use manguto\cms7\libraries\Numbers;

class Configuration
{

    const configuration_dir = 'application/configurations/';

    /**
     * inicializacao da configuracoes
     */
    public function __construct()
    {
        $this->loadConfigurations();

        $this->automaticConstants();
    }

    /**
     * carregamento das configuracoes da aplicacao
     */
    private function loadConfigurations()
    {
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

    /**
     * define constantes automaticas
     */
    private function automaticConstants()
    {
        { // plataforma atual
            $url = ServerHelp::getURL();
            $url = Route::clearURL($url);
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
        { // ip do usuario
            define('APP_USER_IP', ServerHelp::getIp());
        }
        { // ip do usuario mascarado
            {
                $APP_USER_IP_MASKED = explode('.', APP_USER_IP);
                $APP_USER_IP_MASKED = array_map(function ($value) {
                    return Numbers::str_pad_left(strval($value), 3);
                }, $APP_USER_IP_MASKED);
                $APP_USER_IP_MASKED = implode('_', $APP_USER_IP_MASKED);
                //deb($APP_USER_IP_MASKED);
            }
            define('APP_USER_IP_MASKED', $APP_USER_IP_MASKED);
        }
    }
}

?>



