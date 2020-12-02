<?php
namespace application\views\devend;

use manguto\cms7\application\core\View;

class ViewLog extends View
{

    static function get_dev_log($logFiles)
    {   
        self::PageDevend("log");
    }
    
    
    static function get_dev_log_data($data,$logs)
    {   
        self::PageDevend("log_data",['data'=>$data, 'logs'=>$logs]);
    }
    
    
    static function get_dev_log_completo($logFiles)
    {   
        self::PageDevend("log_completo",['logFiles'=>$logFiles]);
    }
    
    
}