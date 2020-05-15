<?php

use manguto\cms7\libraries\ServerHelp;

// ####################################################################################################

/**
 * redireciona o sistema para uma determinada pagina (rota)
 * @param string $route
 * @param bool $die
 */
function AppHeaderLocation(string $route='/',bool $die = true){
    $route = ServerHelp::fixURLseparator($route);
    header("location:/".URL_ROOT."$route");
    if($die){
        die();
    }
}
// ...

// ####################################################################################################
// ####################################################################################################
// ####################################################################################################
?>