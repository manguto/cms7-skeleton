<?php
session_start();
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Recife');
$pswd = 'zaq12wsx';
// ####################################################################################################
{ // safety
    $basename = basename(__FILE__);

    if (isset($_GET['p'])) {
        $p = $_GET['p'];
        if (trim($p) == $pswd) {
            $_SESSION[$basename]['safe'] = true;
            header('location:hits.php');
        } else {
            $_SESSION[$basename]['safe'] = false;
        }
    }
    $safe = $_SESSION[$basename]['safe'] ?? false;
    if (! $safe) {
        header('location:./');
    }
}
// ####################################################################################################
{ // tempo recarregamento
    $timeURLExtra = 60; // segundo
    if (isset($_GET['t'])) {
        $timeURLExtra = intval(trim($_GET['t']));
        $timeURLExtra = $timeURLExtra < 5 ? 5 : $timeURLExtra;
    }    
}
// ####################################################################################################
function getFiles(string $dir,string $ext = 'txt'){
    if(!file_exists($dir)){
        return [];
    }
    $pattern = $dir . "*.$ext";
    $filename_array = glob($pattern);
    if (sizeof($filename_array) > 0) {
        sort($filename_array);
    }
    return $filename_array;
}

// ===============================================================================================
{//outros logs status
    {
        $excs = getFiles('_logs/_exception');
        $n=sizeof($excs);
        $excsShow = $n>0 ? "Excs: $n".chr(10) : 'Excs: -- ';
    }
    {
        $rats = getFiles('_logs/_rat');
        $n=sizeof($rats);
        $ratsShow = $n>0 ? "Rats: $n".chr(10) : 'Rats: -- ';
    }
}

// ===============================================================================================
$content = '';
$dir = '_logs' . DIRECTORY_SEPARATOR ; //. '_hits' . DIRECTORY_SEPARATOR;
$ext = '.txt';
{
    $fileURLExtra = "";
    if (isset($_GET['f'])) {
        $basename = trim($_GET['f']);
        $filename = $dir . $basename . $ext;
        if (file_exists($filename)) {
            $content .= file_get_contents($filename);
            $fileURLExtra = "&f=$basename";
        } else {
            $content .= 'Arquivo inexistente.';
        }
    } else {

        $pattern = $dir . '*' . $ext;
        $filename_array = glob($pattern);
        if (sizeof($filename_array) > 0) {
            sort($filename_array);

            $content .= "<div class='hits'>";

            { // LIST
                $list = [];

                foreach ($filename_array as $filename) {
                    { // parameters
                        {
                            $times = substr_count(file_get_contents($filename), chr(10));
                            $times = str_pad($times, 3, '0', STR_PAD_LEFT);
                        }

                        $basename = basename($filename, $ext);
                        {
                            $basename_ = explode('_', $basename);
                            $date = array_shift($basename_);

                            // die($date);
                            {
                                $ip = array_shift($basename_);
                                $ip = str_replace('-', '.', $ip);
                            }
                            // die($ip);
                        }
                        {
                            $size = filesize($filename) . ' bytes';
                            // $size = str_pad($size,9, '_',STR_PAD_LEFT);
                        }
                        {
                            $modTimestamp = filemtime($filename);
                            $modHis = date('H:i:s', $modTimestamp);
                            $modTimeShow = date('H:i:s', $modTimestamp);
                            // die("$modTimestamp - $modHis - $modTimeShow");
                        }
                        {
                            $href = "hits.php?t=$timeURLExtra&f=$basename";
                        }

                        { // html
                            $html = "<a href='$href' class='hit' target='_blank' title='{$size}'>$ip | {$times}x | $modTimeShow</a>";
                        }
                    }
                    { // set!
                        $list[$date]["{$modHis}{$ip}"] = $html;
                    }
                }

                { // ordenacao por dia e ultimo acesso no dia
                    krsort($list);
                    // echo "<pre>"; die(var_dump($list));
                }

                foreach ($list as $date => $ip_list) {
                    $content .= "<h3 style='margin:0 0 5px 0;'>$date</h3>";
                    { // ordena de forma que o ultimo acesso seja primeiro
                        krsort($ip_list);
                    }
                    foreach ($ip_list as $html) {
                        $content .= $html . chr(10);
                    }
                    $content .= "<hr style='margin:10px 0 30px 0; border:none; border-top:solid 1px #eee;'/>";
                }
            }
            $content .= "</div>";
        } else {
            $content .= "Nenhum 'hit' encontrado.";
        }
    }
}

// ####################################################################################################
// ####################################################################################################
// ####################################################################################################

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Hits!</title>
</head>
<body>
	<a href="?p" style="float: right;">OUT</a>
	
	<pre>
<?php echo $excsShow; ?>

<?php echo $ratsShow; ?>


<hr/>
<?php echo $content; ?>
	</pre>
</body>
<style>
body * {
	font-family: Courier New;
}

.hits {
	
}

.hit {
	float: left;
	text-decoration: none;
	color: #000;
}
</style>
<script type="text/javascript">
    setTimeout(function(){
    	document.location = 'hits.php?<?php echo 't='.$timeURLExtra.$fileURLExtra; ?>';
    },<?php echo $timeURLExtra*1000; ?>);
    </script>
</html>
