<?php
namespace application\core;

use manguto\cms7\libraries\ServerHelp;
use manguto\cms7\libraries\Strings;
use manguto\cms7\libraries\Exception;

class Route
{

    private $method;

    private $raw_url;
    
    private $platform;

    private $url;

    private $parameters;

    public function __construct()
    {

        // obtem o metodo solicitado pela rota
        $this->method = strtoupper(ServerHelp::getRequestMethod());

        // obtem a url informada 
        $this->raw_url = ServerHelp::getURL();        
        
        //transforma a url em uma rota
        $this->url = self::clearURL($this->raw_url);        
        
        //carrega os parametros
        $this->parameters = self::explodeParameters($this->url);
    }

    // ####################################################################################################
    /**
     * remove caracteres indevidos da url
     *
     * @example /admin/log//today/ => admin/log/today
     * @param string $url
     * @return string
     */
    static function clearURL(string $url): string
    {
        //deb($url,0);
        { // remocao de barras repetidas
            $url = Strings::removeRepeatedOccurrences('/', $url);
        }
        { // ajusta a rota (url) de conforme a existencia de servidores virtuais (URL_ROOT)
            $url_init = substr($url, 0, strlen(URL_ROOT));         
            if ($url_init == URL_ROOT) {                
                $url = substr($url, strlen(URL_ROOT)-1);                
            }
        }
        //deb($url,0);
        return $url;
    }

    // ####################################################################################################
    /**
     * explode a url em parametros (delimitador: '/')
     *
     * @param string $url
     * @return array
     */
    private static function explodeParameters(string $url): array
    {
        $return = explode('/', trim($url));
        if(trim($return[sizeof($return)-1])==''){
            array_pop($return);
        }
        return $return;
    }

    // ####################################################################################################
    
    /**
     * realiza os testes quanto a rota solicitada e a mascara do controlador em questao,
     * retornando as variaveis necessarias, e em caso contrario, FALSE.
     * @param string $method
     * @return boolean|string
     */
    private function checkRoute(string $method,string $controller_route_masked)
    {   
        {//parameters
            $controller_route_parameters = self::explodeParameters($controller_route_masked);
        }
        // ######################################################################
        // ################################### teste 1 - metodo (GET, POST, etc.)
        // ######################################################################
        if (strtolower($this->method) != strtolower($method)) {
            return false;
        }
        // ######################################################################
        // ######################## teste 2 - quantidade de parametros envolvidos
        // ######################################################################
        if(sizeof($this->parameters)!=sizeof($controller_route_parameters)){
            return false;
        }
        // ######################################################################
        // ################################ teste 3 - confere os parametros fixos
        // ######################################################################
        $variables = [];
        foreach($controller_route_parameters as $controller_route_parameter_key => $controller_route_parameter_name){
            $route_parameter_name = $this->parameters[$controller_route_parameter_key];
            
            {//testa se o parametro é variavel
                $is_variable = substr($controller_route_parameter_name,0,1)==':';
            }
            if(!$is_variable){
                if($controller_route_parameter_name!=$route_parameter_name){
                    return false;
                }else{
                    //nao eh parametro e esta conforme a mascara da rota
                }
            }else{
                $variables[]=$route_parameter_name;
            }
        }        
        // ######################################################################        
        return $variables;
    }

    // ####################################################################################################
    public function get($url_masc, $function)
    {
        $method = 'get';
        $url_masc = self::clearURL($url_masc);
        $parameters = $this->checkRoute($method,$url_masc);
        if ($parameters !== false) {            
            {//extrai as variaveis (``,$p_0, $p_1, $p_2) e obtem como retorno a quant. de parametros declarados
                $parametersLength = extract($parameters,EXTR_PREFIX_ALL,'p');             
            }
            {//teste e execucao da funcao 
                if($parametersLength==0){
                    $function();
                }else if($parametersLength==1){
                    $function($p_0);
                }else if($parametersLength==2){
                    $function($p_0,$p_1);
                }else if($parametersLength==3){
                    $function($p_0,$p_1,$p_2);
                }else if($parametersLength==4){
                    $function($p_0,$p_1,$p_2,$p_3);
                }else if($parametersLength==5){
                    $function($p_0,$p_1,$p_2,$p_3,$p_4);
                }else{
                    throw new Exception("Foram definidos mais parâmetros do que o programado. Contate o administrador para acréscimo na implementação.");
                }
            }            
        }
    }

    // ####################################################################################################
    public function post($url_masc, $function)
    {
        $method = 'post';
        $url_masc = self::clearURL($url_masc);
        $parameters = $this->checkRoute($method,$url_masc);
        if ($parameters !== false) {
            $function($parameters);
        }
    }
    // ####################################################################################################
    /**
     * retorna a url bruta solicitada
     * @return string
     */
    public function getRawURL():string{
        return $this->raw_url;
    }
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
}