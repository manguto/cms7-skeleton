<?php
namespace application\core;

use manguto\cms7\libraries\Diretorios;
use manguto\cms7\libraries\ServerHelp;
use manguto\cms7\libraries\Files;
use manguto\cms7\libraries\Strings;
use manguto\cms7\libraries\Sessions;
use manguto\cms7\libraries\Logger;

class Controller
{

    private $route;

    private $route_controller = false;

    // ####################################################################################################
    public function __construct()
    {
        Logger::info('Controladora mae inicializada');        
        $this->access = new Access();
        $this->route = new Route();
    }

    // ####################################################################################################
    /**
     * inicia os procedimentos da classe!
     */
    public function Run()
    {
                
        {
            $rawURL = Strings::removeRepeatedOccurrences('/', $this->route->getRawURL());            
            $URL = $this->route->getURL();
            Logger::info("URL (rota) solicitada: '$URL'.");
        }        
        {   
            $controllers = self::GetControllers();
            Logger::info('Controladoras encontradas: '.sizeof($controllers));
        }        
        
        foreach ($controllers as $controller) {
            //Logger::info('Verificando a controladora: '.str_replace(APP_APPLICATION_DIR, '', $controller));
            $controller::RouteMatchCheck($this->route);
        }
        
        {//rota nao encontrada!!!
            $msg = "Não foi possível encontrar a página solicitada (<a href='$rawURL'>$URL</a>).";
            //Alert::setWarning($msgError);
            Logger::error($msg);
            //Logger::info('Redirecionamento para página de erro (404) solicitado...');
            //Controller::HeaderLocation('/404');
            View::PageFrontend("_404",['msg'=>$msg]);
        }        
    }

    // ####################################################################################################

    /**
     * obtem todas as classes controladoras da aplicacao
     *
     * @return string[]
     */
    static function GetControllers()
    {
        $return = [];
        {
            //deb(APP_CONTROLLERS_DIR);
            $filenames = Diretorios::obterArquivosPastas(APP_CONTROLLERS_DIR, true, true, false, [
                'php'
            ]);
            // deb($filenames,0);

            { // verificacao de eventuais controladores modulares
                $modules_files = Diretorios::obterArquivosPastas(APP_MODULES_DIR, true, true, false, [
                    'php'
                ]);
                foreach ($modules_files as $module_file) {
                    $file_content = Files::obterConteudo($module_file);
                    // verifica se o aruqivo eh um arquivo de controle
                    if (strpos($file_content, ' extends Controller') !== false) {
                        // adicao a lista de arquivos de controle
                        $filenames[] = $module_file;
                    }
                }
                // deb($filenames);
            }
        }
        foreach ($filenames as $filename) {
            $return[] = self::GetCallableClassName($filename);
        }
        return $return;
    }

    // ####################################################################################################
    /**
     * obtem uma string com a informacao necessaria para invocar uma classe atraves do nome do arquivo da classe
     * (obs.: desde que o nome da classe seja o mesmo do nome do arquivo (sem a extensao, claro))
     *
     * @param string $filename
     * @return string
     */
    static function GetCallableClassName(string $filename): string
    {
        $return = $filename;
        $return = str_replace(APP_DIRECTORY, '', $return);
        $return = str_replace('.php', '', $return);
        $return = ServerHelp::fixds($return,'\\');
        return $return;
    }

  
    // ####################################################################################################

    /**
     * redireciona o sistema para uma determinada pagina (rota) via GET
     * @param string $route
     * @param bool $die
     */
    static function HeaderLocation(string $route = '/', bool $die = true)
    {          
        {//save iteration! - salva a iteracao para continuacao
            Sessions::set('APP_ITERATION',APP_ITERATION);
        }
        $location = ServerHelp::fixURLseparator('../' . APP_URL_ROOT . $route);
        Logger::info("Redirecionamento via GET solicitado para a URL: '$location'");
        header("location:/" . $location);
        if ($die) {
            die();
        }
    }

    // ####################################################################################################
    /**
     * redireciona o sistema para uma determinada pagina (rota) via POST
     * @param string $URLAbsolute
     * @param array $variables
     */
    static function headerLocationPost(string $URLAbsolute, array $variables = [],bool $die=true)
    {
        {//save iteration! - salva a iteracao para continuacao
            Sessions::set('APP_ITERATION',APP_ITERATION);
        }
        
        $url = APP_URL_ROOT . $URLAbsolute;
        
        $inputs = '';
        foreach ($variables as $key => $value) {

            // ajuste no caso de parametros informados em array (checkboxes...)
            if (! is_array($value)) {
                $inputs .= "$key: <input type='text' name='$key' value='$value' class='form-control mb-2' style='display:none;'>";
            } else {
                $key = $key . '[]';
                foreach ($value as $v) {
                    $inputs .= "$key: <input type='text' name='$key' value='$v' class='form-control mb-2' style='display:none;'>";
                }
            }
        }

        $html = "<!DOCTYPE html>
                <html>
                    <head>
                        <title>REDIRECTION...</title>
                    </head>
                    <body>
                        <section>
                        	<div class='container'>
                        		<form method='post' action='$url' id='postRedirect' style='display:none;'>
                                    $inputs
                        			<input type='submit' value='CLIQUE AQUI PARA CONTINUAR...' style='display:none;'>
                        		</form>
                        	</div>
                        </section>
                    </body>
                </html>
                <script type='text/javascript'>
                    (function() {
                        document.getElementById('postRedirect').submit();
                    })();
                </script>";
        
        echo $html;
        Logger::info("Redirecionamento via POST solicitado para a URL: '$url'");
        if($die){
            exit();
        }
    }
       
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
}

?>