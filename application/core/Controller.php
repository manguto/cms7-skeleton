<?php
namespace application\core;

use manguto\cms7\libraries\Diretorios;
use manguto\cms7\libraries\ServerHelp;
use manguto\cms7\libraries\File;
use manguto\cms7\libraries\Strings;
use manguto\cms7\libraries\Sessions;
use manguto\cms7\libraries\Logger;

class Controller
{

    private $route;

    private $route_controller = false;
   
    // texto presente nos arquivos das controladoras modelos (nao interpretar)     
    const ModelControllerFilenameTags = [
        'xxx',
        'yyy',
        'zzz'
    ];

    // ####################################################################################################
    public function __construct()
    {   
        Logger::info('Controladora Geral inicializacao');
        $this->access = new Access();
        $this->route = new Route();
    }

    // ####################################################################################################
    /**
     * executa os procedimentos fundamentais 
     * para a montagem da estrutura da aplicacao 
     * com o tratamento de excecao.
     */
    public function Run()
    {
        Logger::info('Controladora Geral execucao');
        {//tratamento da rota solicitada
            $rawURL = Strings::removeRepeatedOccurrences('/', $this->route->getRawURL());
            $URL = $this->route->getURL();
            //$URL = substr($URL, 1);
            Logger::info("Rota solicitada \"$URL\"");
        }
        {//levantamento das controladoras existentes
            //Logger::info('Procurando controladoras...');
            $controllers = self::GetControllers();
            //deb($controllers,0);
            $nc = sizeof($controllers);
            Logger::info("$nc controladoras encontradas");
        }
        //deb(getcwd(),0);
        {//verificacao quanto a existencia de um controladora responsavel para a rota
            foreach ($controllers as $controller) {
                //Logger::info("Testando a controladora '$controller'");
                $controller::RouteMatchCheck($this->route);
                if($this->route->route_found==true){
                    Logger::success('Controladora executada: ' . basename($controller));
                    break;
                }
            }            
        }        
        {//verificacao e procedimento quanto a rotas não tratadas!            
            // rota nao encontrada?
            if($this->route->route_found==false){                
                $msg = "A página solicitada não foi encontrada (<a href='$rawURL'>$URL</a>).";
                Logger::error($msg);
                View::PageFrontend("_404", [
                    'msg' => $msg
                ]);
            }
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

        { // OBTENCAO DAS CONTROLADORES OFICIAIS
            $controller_filename_array = Diretorios::obterArquivosPastas(APP_CONTROLLERS_DIR, true, true, false, [
                'php'
            ]);
        }
        { // OBTENCAO DE EVENTUAIS CONTROLADORES MODULARES
            $modules_files = Diretorios::obterArquivosPastas(APP_MODULES_DIR, true, true, false, [
                'php'
            ]);
            foreach ($modules_files as $module_file) {

                $file_content = File::getContent($module_file);
                // verifica se o aruqivo eh um arquivo de controle
                if (strpos($file_content, ' extends Controller') !== false) {
                    // adicao a lista de arquivos de controle
                    $controller_filename_array[] = $module_file;
                }
            }
            // deb($filenames);
        }

        { // JUNCAO DAS CONTROLADORAS OFICIAIS E GERAIS E TRATAMENTO DOS MODELOS
            foreach ($controller_filename_array as $filename) {
                { // evita arquivos modelo (ControlerXxx.php)
                    $continue = false;
                    foreach (self::ModelControllerFilenameTags as $tag) {
                        if (strpos(strtolower($filename), $tag) !== false) {
                            $continue = true;
                        }
                    }
                    if ($continue) {
                        continue;
                    }
                }
                $return[] = self::GetCallableClassName($filename);
            }
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
        $return = str_replace(APP_ROOT_PATH, '', $return);
        $return = str_replace('.php', '', $return);
        $return = ServerHelp::fixds($return, '\\');
        return $return;
    }

    // ####################################################################################################

    /**
     * redireciona o sistema para uma determinada pagina (rota) via GET
     *
     * @param string $route
     * @param bool $die
     */
    static function HeaderLocation(string $route = '/', bool $die = true)
    {
        { // save iteration! - salva a iteracao para continuacao
            Sessions::set('APP_TICKET_ID', APP_TICKET_ID);
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
     *
     * @param string $URLAbsolute
     * @param array $variables
     */
    static function headerLocationPost(string $URLAbsolute, array $variables = [], bool $die = true)
    {
        { // save iteration! - salva a iteracao para continuacao
            Sessions::set('APP_TICKET_ID', APP_TICKET_ID);
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
        if ($die) {
            exit();
        }
    }

    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
}

?>