<?php
namespace application\controllers\devend;

use application\core\Access;
use application\core\Controller;
use application\core\Route;
use application\core\View;
use manguto\cms7\libraries\Diretorios;
use manguto\cms7\libraries\ServerHelp;
use manguto\cms7\libraries\Files;

class ControllerLogs extends Controller
{

    const logsDir = 'logs' . DS;

    static function RouteMatchCheck(Route $route)
    {
        // ##################################################
        $route->get('/dev/logs', function () {
            Access::Concierge("dev");
            {
                $logs = self::getHistory();
            }
            View::PageDevend('logs/home', get_defined_vars());
        });
        // ##################################################
        $route->get('/dev/logs/:day', function ($day) {
            Access::Concierge("dev");
            {
                $logs = self::getHistory($day);                
            }
            View::PageDevend('logs/ips', get_defined_vars());
        });
        // ##################################################
        $route->get('/dev/logs/:day/:ip', function ($day,$ip) {
            Access::Concierge("dev");
            {
                $logs = self::getHistory($day,$ip);
                //deb($logs);
            }
            View::PageDevend('logs/reg', get_defined_vars());
        });
        // ##################################################
    }

    // ####################################################################################################
    // ############################################################################################ PRIVATE
    // ####################################################################################################
    static private function getHistory($day = '',$ip='')
    {
        $return = [];
        {
            $path = self::logsDir;
            if($day!=''){
                $path.= $day . DS;
            }
            if($ip!=''){
                $path.= $ip . DS;
            }
            //deb($path);
        }
        if($day=='' || $ip==''){
            $logs = Diretorios::obterArquivosPastas($path, false, false, true);
        }else{
            $logs = Diretorios::obterArquivosPastas($path, false, true, false, ['txt']);
        }
        
        
        foreach ($logs as $path) {
            {
                {
                    $info = basename($path);
                }
                $key = $info;
                $show = ServerHelp::fixds($info, '/');
                
                if($day==''){
                    //quantidade de PASTAS
                    $n = Diretorios::obterArquivosPastas($path, false, false, true);
                    $size = sizeof($n);
                }else{
                    if($ip==''){
                        //quantidade de ARQUIVOS
                        $n = Diretorios::obterArquivosPastas($path, false, true, false,['txt']);
                        $size = sizeof($n);
                    }else{
                        //quantidade de LINHAS
                        $n = Files::getFileSize($path);
                        $size = $n;
                    }                    
                }
                //deb($ip,0); deb($path,0); deb($filesFolders);                
            }
            $return[$key] = [
                'show'=>$show,
                'size'=>$size,
            ];
        }
        // deb($return);
        return $return;
    }
}

?>