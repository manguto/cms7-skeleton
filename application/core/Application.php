<?php
namespace application\core;

use manguto\cms7\libraries\Logger;

class Application
{
    
    private $logger;
    
    private $configuration;

    private $controller;
    
    public function __construct()
    {   
        // ######################################################        
        {   
            $this->configuration = new Configuration();
        }   
        // ######################################################
        Logger::info(str_repeat('@', 150));
        Logger::info('Applicacao inicializada - '.__METHOD__);
        Logger::info('Configuracao inicializada');        
        Logger::info('Constantes fundamentais registradas');
        // ######################################################
        {
            $this->controller = new Controller();        
        }
        // ######################################################
    }

    public function Run()
    {
        Logger::info('Execucao da aplicacao - '.__METHOD__);
        // ######################################################
        $this->controller->Run();
        // ######################################################
    }
}

?>