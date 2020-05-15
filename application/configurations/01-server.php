<?php
define("APP_VIRTUAL_HOST", false);

define("DS", DIRECTORY_SEPARATOR);

{
    $actualDirectory = __DIR__;
    $level = 2;
    $ROOT = dirname($actualDirectory, $level) . DIRECTORY_SEPARATOR;    
}
define("APP_ROOT", $ROOT);

define("APP_FOLDERNAME", basename(APP_ROOT));

{
    if (APP_VIRTUAL_HOST) {
        $URL_ROOT = "";
    } else {
        $URL_ROOT = '/'.APP_FOLDERNAME . '/';
    }
}
define("URL_ROOT", $URL_ROOT);

define("APP_DATE",date('Ymd'));

define("APP_TIME",date('His'));

define("APP_UNIQID",uniqid());

define("APP_ITERATION",APP_DATE.'_'.APP_TIME.'_'.APP_UNIQID);


?>