<?php
require_once 'lib.php';

$files = obterArquivosPastas('.', true, true, false, [
    'txt'
]);

$content = file_get_contents(array_pop($files));

echo "<pre>";
echo $content; 
echo "</pre>";
?>
