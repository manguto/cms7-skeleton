<?php
namespace application\core;


class View
{

    const FRONTEND_TEMPLATE_DIR = APP_TPL_DIR . 'frontend' . DS;

    const BACKEND_TEMPLATE_DIR = APP_TPL_DIR . 'backend' . DS;

    const DEVEND_TEMPLATE_DIR = APP_TPL_DIR . 'devend' . DS;

    const EXTRA_TEMPLATE_DIR = APP_TPL_DIR . '_extra' . DS;

    //const MODULES_DIR = '..' . DS . '..' . DS . 'modules' . DS;
    //const MODULES_DIR = APP_MODULES_DIR;

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
        return self::PageFrontend(self::GET_MODULES_DIR_REFERENCE() . $templateFilename, $parameters, $toString);
    }

    static function PageBackendModule(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        return self::PageBackend(self::GET_MODULES_DIR_REFERENCE() . $templateFilename, $parameters, $toString);
    }

    static function PageDevendModule(string $templateFilename, array $parameters = [], bool $toString = false)
    {
        return self::PageDevend(self::GET_MODULES_DIR_REFERENCE() . $templateFilename, $parameters, $toString);
    }

    // ####################################################################################################
    // ##################################################################################### STATIC PRIVATE
    // ####################################################################################################
    static private function PageRun(Page $page, bool $toString)
    {   
        return $page->run($toString);
    }
    // ####################################################################################################
    /**
     * obtem o endereço do diretorio dos modulos utilizando como referencia os diretorio dos templates (APP_TPL_DIR) 
     * @return string
     */
    static private function GET_MODULES_DIR_REFERENCE():string{
        return str_repeat('..'.DS, sizeof(explode(DS, APP_TPL_DIR))).str_replace(APP_TPL_DIR, '', APP_MODULES_DIR);        
    }
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
}