<?php
namespace application\core;

class Application
{
    private $configuration;

    private $controller;

    public function __construct()
    {
        // ######################################################
        $this->configuration = new Configuration();
        // ######################################################
        $this->controller = new Controller();
        // ######################################################
    }

    public function Run()
    {
        // ######################################################
        $this->controller->Run();
        // ######################################################
    }
}

?>