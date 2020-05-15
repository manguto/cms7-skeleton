<?php
namespace application\controllers\devend;

use manguto\cms7\libraries\Logs;
use manguto\cms7\libraries\Datas;
use manguto\cms7\libraries\Calendario;
use manguto\cms7\libraries\Numbers;
use application\views\devend\ViewLog;
use application\core\Controller; use application\core\Route; 

class Log extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/log', function () {
            self::PrivativeDevZone();
            AppHeaderLocation('/dev/log/dia/' . date(Logs::formato_data_arquivo_diario));
            exit();
        });

        $route->get('/dev/log/dia/:day', function ($day) {
            self::PrivativeDevZone();
            {
                // deb($day);
                $date = new Datas($day, Logs::formato_data_arquivo_diario);
                $datashow = $date->getDate('d/m/Y');
                $ano = $date->getDate('Y');
            }

            // deb($day);
            $dayLogs = Logs::getDayLogs($day);
            // deb($dayLogs);

            ViewLog::PageDevend('log_dia', get_defined_vars());
        });

        $route->get('/dev/log/ano/:ano', function ($ano) {
            self::PrivativeDevZone();
            {
                $ano_ant = intval($ano) - 1;
                $ano_post = intval($ano) + 1;
            }
            {
                $calendario = new Calendario($ano);
                $calendario = $calendario->HTML_ObterAno();
                // debc($calendario);
                // debc(Calendario::defaultCSS);
            }
            {
                $logs = Logs::getYearLogs($ano);
                // deb($logs);
                $js = [];
                foreach ($logs as $log) {
                    {
                        // deb($log);
                        extract($log);
                        $MONTH = intval($MONTH);
                        $DAY = intval($DAY);
                    }
                    {
                        $MES2D = Numbers::str_pad_left($MONTH);
                        $DIA2D = Numbers::str_pad_left($DAY);
                        $ymd = $YEAR . $MES2D . $DIA2D;
                        $href = '/dev/log/dia/' . $ymd;
                        $link = '<a href="' . $href . '" title="Clique para visualizar os registros desta data.">LOG<a>';
                    }
                    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                    $js[$MONTH . $DAY] = "$('.mes-$MONTH .dia-$DAY .conteudo').html('$link'); ";
                    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
                }
                $js = implode(chr(10), $js);
                // deb($js);
            }
            // debc($js);
            ViewLog::PageDevend('log_ano', get_defined_vars());
        });
    }

    // ===================================================================================
    // ===================================================================================
    // ===================================================================================
}

?>