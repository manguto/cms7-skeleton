<?php
namespace application\controllers\devend;

use manguto\cms7\application\core\Access;
use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\application\core\View;
use manguto\cms7\libraries\Diretorios;
use manguto\cms7\libraries\ServerHelp;
use manguto\cms7\libraries\File;

class ControllerLogs extends Controller
{

    const logsDir = '_logs' . DS;

    //ESPECIFICACAO DA VISIBILIDADE DOS LOGS! (em producao => true)
    const privative_access = true;
    
    static function RouteMatchCheck(Route $route)
    {
        // ##################################################
        $route->get('/dev/logs', function () {
            if(self::privative_access){
                Access::Concierge("dev");
            }
            {
                $logs = self::getHistory();
            }
            View::PageDevend('logs/home', get_defined_vars());
        });
        // ##################################################
        $route->get('/dev/logs/:day', function ($day) {
            if(self::privative_access){
                Access::Concierge("dev");
            }
            {
                $logs = self::getHistory($day);                
            }
            View::PageDevend('logs/ips', get_defined_vars());
        });
        // ##################################################
        $route->get('/dev/logs/:day/:ip', function ($day,$ip) {
            if(self::privative_access){
                Access::Concierge("dev");
            }
            {
                $logs = self::getHistory($day,$ip);
                //deb($logs);
                $logsDir = ControllerLogs::logsDir;
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
                        $n = File::getFileSize($path);
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
        //arsort($return);
        asort($return);
        // deb($return);
        return $return;
    }
}




?>