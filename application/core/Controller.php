<?php
namespace application\core;

use manguto\cms7\libraries\Diretorios;
use manguto\cms7\libraries\ServerHelp;
use manguto\cms7\libraries\Files;
use manguto\cms7\libraries\Exception;
use manguto\cms7\libraries\ProcessResult;

class Controller
{

    private $route;

    private $route_controller = false;

    // ####################################################################################################
    public function __construct()
    {
        $this->access = new Access();
        $this->route = new Route();
    }

    // ####################################################################################################
    /**
     * inicia os procedimentos da classe!
     */
    public function Run()
    {
        $controllers = self::GetControllers();
        foreach ($controllers as $controller) {
            $controller::RouteMatchCheck($this->route);
        }
        // die($this->route->clearURL($this->route->getRawURL()));
        // die($this->route->getRawURL());
        // die(getcwd());

        $throwException = true;
        $msg = "Não foi possível encontrar uma rota para o endereço solicitado ('{$this->route->getRawURL()}').";
        if ($throwException) {
            ProcessResult::setError($msg);
            Controller::HeaderLocation('/');
        } else {
            throw new Exception($msg);
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
            $filenames = Diretorios::obterArquivosPastas(APP_CONTROL_DIR, true, true, false, [
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
        $return = str_replace(APP_ROOT, '', $return);
        $return = str_replace('.php', '', $return);
        return $return;
    }

    // ####################################################################################################
    static function PrivativeZone()
    {}

    // ####################################################################################################
    static function PrivativeAdminZone()
    {}

    // ####################################################################################################
    static function PrivativeDevZone()
    {}

    // ####################################################################################################

    /**
     * redireciona o sistema para uma determinada pagina (rota) via GET
     * @param string $route
     * @param bool $die
     */
    static function HeaderLocation(string $route = '/', bool $die = true)
    {
        $location = ServerHelp::fixURLseparator('../' . URL_ROOT . $route);
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
        $url = URL_ROOT . $URLAbsolute;

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
        
        if($die){
            exit();
        }
    }
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
}

?>