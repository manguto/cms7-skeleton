<?php


// ####################################################################################################
// ##################################################################################### CONFIGURACOES
// ####################################################################################################

define("APP_FULL_NAME", "Controle de Acesso");

define("APP_SHORT_NAME", "Controle de Acesso à Unidade");

define("APP_ABREV", "CAc");

define("APP_TITLE", APP_ABREV . " | " . APP_SHORT_NAME);

//define("APP_DOMAIN", "http://suporte.uast.ufrpe.br/tools/controle_acesso/");
define("APP_DOMAIN", "http://l.manguto.com/controle_acesso/");

define("APP_EMAIL", "marcos.torres@ufrpe.br");

define("APP_EMAIL_ADMIN", "marcos.torres@ufrpe.br");

// ######################################################################
// ##################################################### BANCOS DE DADOS
// ######################################################################

define("APP_DATABASE", [
    0 => [
        "hostname" => "localhost",
        "username" => "username",
        "password" => "**********",
        "database" => "database",
        "chartset" => "utf8"
    ]
]);

// ######################################################################
// ################################################################# ...
// ######################################################################

?>