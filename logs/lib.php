<?php

// #############################################################################################################################################
function backtraceFix(string $backtrace, $sortAsc = true)
{
    $backtrace = str_replace("'", '"', $backtrace);
    { // revert order
        $backtrace_ = explode(chr(10), $backtrace);
        if ($sortAsc == false) {
            krsort($backtrace_);
        }
        $backtrace = implode(chr(10), $backtrace_);
    }
    return $backtrace;
}
// #############################################################################################################################################
function get_backtrace(): string
{
    // obtem backtrace
    $trace = debug_backtrace();
    // removao da primeira linha relativa a chamada a esta mesma funcao
    array_shift($trace);
    // inversao da ordem de exibicao
    krsort($trace);
    $return = '';
    $step = 1;
    foreach ($trace as $t) {
        
        if (isset($t['file'])) {
            $file = $t['file'];
            $line = $t['line'];
            $func = $t['function'];
            $return .= "#" . $step ++ . " => $func() ; $file ($line)\n";
        }
    }
    // identacao
    $return = str_replace(';', '', $return);
    
    return $return;
}
// #############################################################################################################################################
/**
 * DEBUG - IMPRIME a representacao HTML da variavel informada
 *
 * @param bool $die
 * @param bool $backtrace
 */
function deb($var, bool $die = true, bool $backtrace = true)
{

    // backtrace show?
    if ($backtrace) {
        $backtrace = backtraceFix(get_backtrace(), false);
    } else {
        $backtrace = '';
    }

    // var_dump to string
    ob_start();
    var_dump($var);
    $var = ob_get_clean();

    // remove a kind of break lines
    { // values highligth
        $var = str_replace('=>' . chr(10), ' => <span class="varContent">', $var);
        $var = str_replace(chr(10), '</span>' . chr(10), $var);
    }
    { // remove spaces
        while (strpos($var, '  ')) {
            $var = str_replace('  ', ' ', $var);
        }
    }
    { // parameter name highligth
        $var = str_replace('["', '[<span class="varName">', $var);
        $var = str_replace('"]', '</span>]', $var);
    }
    { // content highligth
        $var = str_replace('{', '<div class="varArrayContent">{', $var);
        $var = str_replace(' }', '}</div>', $var);
    }
    { // bold values
        $var = str_replace(' "', ' "<span class="varContentValue">', $var);
        $var = str_replace('"</', '</span>"</', $var);
    }

    echo "
<pre class='deb' title='$backtrace'>$var</pre>
<style>
.deb {	line-height:17px;}
.deb .varName {	background: #ffb;}
.deb .varContent {}
.deb .varContentValue {	background: #fbb;    padding: 0px 5px;    border-radius:2px;}
.deb .varArrayContent {	border-bottom: solid 1px #ccc;	border-left: solid 1px #eee;	padding: 0px 0px 5px 5px;	margin: 0px 0px 0px 10px;    cursor:pointer;}
.deb .varArrayContent:hover {    border-color:#555;}
</style>";
    if ($die) {
        die();
    }
}

// ####################################################################################################
function fixds(string $path, string $DIRECTORY_SEPARATOR = DIRECTORY_SEPARATOR): string
{
    $path = str_replace('/', $DIRECTORY_SEPARATOR, $path);
    $path = str_replace('\\', $DIRECTORY_SEPARATOR, $path);
    while (strpos($path, $DIRECTORY_SEPARATOR . $DIRECTORY_SEPARATOR) !== false) {
        $path = str_replace($DIRECTORY_SEPARATOR . $DIRECTORY_SEPARATOR, $DIRECTORY_SEPARATOR, $path);
    }
    return $path;
}

// ####################################################################################################
function obterArquivosPastas(string $path, bool $recursive, bool $filesAllowed, bool $foldersAllowed, $allowedExtensions = array(), $throwException = true)
{
    // deb($path,0);
    $path = trim($path) == '' ? '.' . DIRECTORY_SEPARATOR : trim($path);
    $path = fixds($path);

    { // allowed extensions
        if (! is_array($allowedExtensions)) {
            $allowedExtensions = [
                strval($allowedExtensions)
            ];
        }
    }

    if ($filesAllowed == false) {
        $allowedExtensions = false;
    } else {
        if (is_string($allowedExtensions)) {
            $allowedExtensions = [
                $allowedExtensions
            ];
        } else if (! is_array($allowedExtensions)) {
            throw new Exception("O tipo do parâmetro 'allowedExtensions' não é permitido => '" . gettype($allowedExtensions) . "' (Permitidos: array, string).");
        }
        // deb($allowedExtensions,0);
    }

    $return = array();

    if (file_exists($path)) {

        $dh = opendir($path);

        while (false !== ($filename = readdir($dh))) {

            if ($filename == '.' || $filename == '..') {
                continue;
            }

            // impede o retorno do nome do arquivo com um diretorio esquisito (ex.: ./index.php, ./config.php)
            if ($path == '.' . DIRECTORY_SEPARATOR) {
                $filename = $filename;
            } else {
                $filename = $path . DIRECTORY_SEPARATOR . $filename;
            }

            $filename = fixds($filename);

            if (is_dir($filename)) {
                // --- ADICIONA FOLDERNAME
                $return[] = $filename . DIRECTORY_SEPARATOR;

                if ($recursive) {
                    $filename = obterArquivosPastas($filename, $recursive, $filesAllowed, $foldersAllowed, $allowedExtensions);
                    if (sizeof($filename) > 0) {
                        foreach ($filename as $f) {
                            // --- ADICIONA SUB-FILENAMES
                            $return[] = $f;
                        }
                    }
                }
            } else {
                // --- ADICIONA FILENAME
                $return[] = $filename;
            }
        }

        foreach ($return as $k => $filename) {
            if (is_dir($filename) && ! $foldersAllowed) {
                unset($return[$k]);
            }
            if (is_file($filename) && $allowedExtensions === false) {
                unset($return[$k]);
            }
            if (is_file($filename) && $allowedExtensions !== false) {
                $extension = getExtension($filename);

                // deb("$path => $extension",0);
                // debug($allowedExtensionArray,0);
                if (sizeof($allowedExtensions) > 0 && ! in_array($extension, $allowedExtensions)) {
                    unset($return[$k]);
                }
            }
        }
    } else {
        if ($throwException) {
            throw new Exception("Diretório não encontrado ($path).");
        }
    }

    return $return;
}

// ####################################################################################################
function getExtension(string $path)
{
    return pathinfo($path, PATHINFO_EXTENSION);
}
// ####################################################################################################
// ####################################################################################################
// ####################################################################################################
?>