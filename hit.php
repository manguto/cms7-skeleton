<?php

// ####################################################################################################
// ############################################################################################## CLASS
// ####################################################################################################
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Recife');

class Hit
{

    const pathPrefix = '/var/www/html/';

    public function __construct(string $fixURLContent = '')
    {
        try {
            {
                $filename = self::getFilename();
                $content = self::getContent($fixURLContent);
            }
            if (! file_put_contents($filename, $content, FILE_APPEND)) {
                throw new Exception(error_get_last());
            }
        } catch (\Throwable | \Exception | \Error $e) {
            die(self::ThrowableHand($e));
        }
    }

    static function deb($var, $die = true)
    {
        echo var_dump($var) . "<hr/>";
        if ($die) {
            die('...');
        }
    }

    /**
     * obtem o IP do usuario
     *
     * @return string
     */
    static private function getIp()
    {
        // whether ip is from share internet
        if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } // whether ip is from proxy
        elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } // whether ip is from remote address
        else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        return $ip_address;
    }

    // ####################################################################################################
    static private function ThrowableHand($e)
    {
        if (is_object($e)) {
            // tipo do evento (classe)
            $event_class = get_class($e);

            if (strpos($event_class, 'Exception') !== false) {
                { // EXCEPTION
                    return $e->getMessage();
                }
            } else if (strpos($event_class, 'Error') !== false) {
                { // ERROR
                    return "$e";
                }
            } else if (strpos($event_class, 'Notice') !== false) {
                { // NOTICE
                    return "$e";
                }
            } else {
                return "Evento ($event_class): " . strval($e);
            }
        } else {

            if (is_string($e)) {
                { // ERROR
                    return "$e";
                }
            } else if (is_array($e)) {
                { // ARRAY
                    return implode(chr(10), $e);
                }
            } else {
                return "Evento ($event_class): " . strval($e);
            }
        }
    }

    // ####################################################################################################

    /**
     * retorna o ip do usuario atual no padrao DDD_DDD_DDD_DDD
     * onde o separador (spacer) pode ser alterado
     *
     * @param string $spacer
     * @return string
     */
    static private function getStandardizedIP(string $spacer = '_'): string
    {
        $ip = self::getIp();
        $ip_ = explode('.', $ip);
        $return = [];
        foreach ($ip_ as $ip_piece) {
            $return[] = str_pad($ip_piece, 3, '0', STR_PAD_LEFT);
        }
        return implode($spacer, $return);
    }

    // ####################################################################################################
    static private function filter_input(int $INPUT_, string $variable_name, int $FILTER_VALIDATE_ = NULL, $options = NULL, bool $throwException = false)
    {
        $return = self::filter_input($INPUT_, $variable_name, $FILTER_VALIDATE_, $options);
        $return = substr($return, 1);
        if ($throwException == true && ($return == false || $return == NULL)) {
            die("Não foi possível obter o conteúdo da variável solicitada ('$variable_name').");
        }
        return $return;
    }

    // ####################################################################################################
    /**
     *
     * @return string
     */
    static private function getFilename()
    {
        $dir = '_logs' . DIRECTORY_SEPARATOR ;//. '_hits' . DIRECTORY_SEPARATOR;
        if(file_exists($dir)==false){
        	mkdir($dir,'0777');
        }
        $basename = date('Y-m-d') . '_' . self::getStandardizedIP('-') . '.txt';
        $filename = "{$dir}{$basename}";
        return $filename;
    }

    // ####################################################################################################
    static private function getContent(string $fixURLContent = '')
    {
        {
            $date = date('H:i:s d/m/Y');
            {
                // self::deb($fixURLContent,0);
                // remocao inicio caminho
                $fix = str_replace(self::pathPrefix, '', $fixURLContent);
                // self::deb($fix,0);
                $url = self::getURL();
                // self::deb($url);
                $url = str_replace($fix, '', $url);
                $url = str_replace('///', '/', $url);
                // self::deb($url);
            }
            {
                $parameters = [];
                { // GET
                    if (sizeof($_GET) > 0) {
                        $temp = [];
                        foreach (array_keys($_GET) as $key) {
                            $temp[] = $key;
                        }
                        $parameters[] = 'GET: ' . implode(',', $temp);
                    }
                }
                { // POST
                    if (sizeof($_POST) > 0) {
                        $temp = [];
                        foreach (array_keys($_POST) as $key) {
                            $temp[] = $key;
                        }
                        $parameters[] = 'POST: ' . implode(',', $temp);
                    }
                }
                { // FILES
                    if (sizeof($_FILES) > 0) {
                        $temp = [];
                        foreach (array_keys($_FILES) as $key) {
                            $temp[] = $key;
                        }
                        $parameters[] = 'FILES: ' . implode(',', $temp);
                    }
                }
                $parameters = sizeof($parameters) > 0 ? ' | ' . implode('>', $parameters) : '';
            }
        }
        $data = "{$date} | {$url}{$parameters}" . chr(10);
        return $data;
    }

    // ####################################################################################################
    /**
     * Obtem a rota solicitada via URL
     *
     * @return string
     */
    static private function getURL(): string
    {
        $REQUEST_URI = $_SERVER['REQUEST_URI'] ?? false;
        if ($REQUEST_URI !== false) {
            $return = $REQUEST_URI;
        } else {
            $return = self::filter_input(INPUT_SERVER, 'REQUEST_URI');
        }
        return $return;
    }
}

// ####################################################################################################
// ############################################################################## SALVAMENTO DO ARQUIVO
// ####################################################################################################
$hit = new Hit(__DIR__);
// ####################################################################################################
// ####################################################################################################
// ####################################################################################################
?>
