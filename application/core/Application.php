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
        Logger::info(str_repeat('@', 80).' - START! ');
        // ######################################################        
        {   
            $this->configuration = new Configuration();
            Logger::info('Configuração inicializada!');
        }           
        // ######################################################        
        {
            $this->controller = new Controller();      
            Logger::info('Controladora inicializada!');
        }
        // ######################################################
    }

    public function Run()
    {
        Logger::info('Execucao da aplicacao inicializada!');
        // ######################################################
        {
            $this->controller->Run();
        }
        // ######################################################
        
    }
}

?>