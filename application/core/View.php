<?php
namespace application\core;

class View
{

    const FRONTEND_DIR = APP_TPL_DIR . 'frontend' . DS;

    const BACKEND_DIR = APP_TPL_DIR . 'backend' . DS;

    const DEVEND_DIR = APP_TPL_DIR . 'dev' . DS;

    const OTHERS_DIR = APP_TPL_DIR;

    const MODULES_DIR = '..' . DS . '..' . DS . 'modules' . DS;

    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
    static function PageFrontend(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        $page = new Page(self::FRONTEND_DIR);
        $page->loadTpl($templateFilename, $parameters);
        return $page->run($toString);
    }

    static function PageBackend(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        $page = new Page(self::BACKEND_DIR);
        $page->loadTpl($templateFilename, $parameters);
        return $page->run($toString);
    }

    static function PageDevend(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        $page = new Page(self::DEVEND_DIR);
        $page->loadTpl($templateFilename, $parameters);
        return $page->run($toString);
    }

    static function PageOther(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        $page = new Page(self::OTHERS_DIR);
        $page->loadTpl($templateFilename, $parameters);
        return $page->run($toString);
    }

    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
    
    static function PageFrontendModule(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        return self::PageFrontend(self::MODULES_DIR . $templateFilename, $parameters, $toString);
    }

    static function PageBackendModule(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        return self::PageBackend(self::MODULES_DIR . $templateFilename, $parameters, $toString);
    }

    static function PageDevendModule(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        return self::PageDevend(self::MODULES_DIR . $templateFilename, $parameters, $toString);
    }

    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
}