<?php
namespace application\core;

use manguto\cms7\libraries\Logger;

class View
{

    const FRONTEND_TEMPLATE_DIR = APP_TPL_DIR . 'frontend' . DS;

    const BACKEND_TEMPLATE_DIR = APP_TPL_DIR . 'backend' . DS;

    const DEVEND_TEMPLATE_DIR = APP_TPL_DIR . 'devend' . DS;

    const EXTRA_TEMPLATE_DIR = APP_TPL_DIR . '_extra' . DS;

    const MODULES_DIR = '..' . DS . '..' . DS . 'modules' . DS;

    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
    static function PageFrontend(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        $page = new Page(self::FRONTEND_TEMPLATE_DIR);
        $page->loadTpl($templateFilename, $parameters);
        return self::PageRun($page, $toString);
    }

    static function PageBackend(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        $page = new Page(self::BACKEND_TEMPLATE_DIR);
        $page->loadTpl($templateFilename, $parameters);
        return self::PageRun($page, $toString);
    }

    static function PageDevend(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        $page = new Page(self::DEVEND_TEMPLATE_DIR);
        $page->loadTpl($templateFilename, $parameters);
        return self::PageRun($page, $toString);
    }

    static function PageExtra(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        $page = new Page(self::EXTRA_TEMPLATE_DIR);
        $page->loadTpl($templateFilename, $parameters);
        return self::PageRun($page, $toString);
    }

    // ####################################################################################################
    // ####################################################################################### MODULE PAGES
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
    // ##################################################################################### STATIC PRIVATE
    // ####################################################################################################
    static private function PageRun(Page $page, bool $toString)
    {
        Logger::success(str_repeat('@', 70) . ' - END! ' . APP_ITERATION);
        return $page->run($toString);
    }
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
}