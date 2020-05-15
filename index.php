<?php
session_start();
require_once ("vendor/autoload.php");
use application\core\Application;
use manguto\cms7\libraries\Exception;

try {

    { // APLICATION

        { // INIT!
            $Application = new Application();
        }

        { // RUN!
            $Application->Run();
        }
    }
} catch (\Throwable | Exception | \Error $e) {

    Exception::handleEvent($e);
}

?>