<?php
namespace application\controllers\devend;

use manguto\cms7\libraries\Files;
use application\core\View;
use application\core\Controller;
use application\core\Route;
use manguto\cms7\libraries\Diretorios;
use application\core\Access;

class ControllerDocs extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/docs', function () {
            Access::CheckUserProfiles(["dev"]);
            View::PageDevend('docs', self::docs_parameters());
        });

        $route->get('/dev/docs/:page', function ($page) {
            Access::CheckUserProfiles(["dev"]);            
            //View::PageDevend('docs_page', self::docs_page_parameters($page));
            View::PageDevend('docs/docs_page', get_defined_vars());
        });
    }

    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
    static private function docs_parameters()
    {
        $folder = APP_DIRECTORY . 'docs';
        $samples = [];
        // deb($folder);
        if (file_exists($folder)) {
            $htmls = Diretorios::obterArquivosPastas($folder, false, true, false, [
                'html'
            ]);

            foreach ($htmls as $html) {
                $filename = Files::getBaseName($html, false);
                $samples[$filename] = ucfirst($filename);
            }
        }
        // deb($samples);

        return get_defined_vars();
    }

    static private function docs_page_parameters($page)
    {
        $folder = APP_DIRECTORY . 'docs';
        {
            $pageTitle = ucfirst($page);
        }
        {
            // $pageContent = Files::obterConteudo(APP_PATH.'docs/'.$page.'.html');
            $include = "../../../../../$folder/$page";
        }
        return get_defined_vars();
    }
}

?>