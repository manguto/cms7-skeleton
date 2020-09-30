<?php

function getIP():string{
    { // whether ip is from share internet
        if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } // whether ip is from proxy
        elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } // whether ip is from remote address
        else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
    }
    return $ip_address;
}

function formatIP(string $ip_address,string $glue='-'):string
{    
    { // formatacao
        $ip_address_array = explode('.', $ip_address);
        foreach ($ip_address_array as $k => $ip_part) {
            $ip_address_array[$k] = str_pad($ip_part, 3, '0', STR_PAD_LEFT);
        }
        $return = implode($glue, $ip_address_array);
    }
    return $return;
}
// ####################################################################################################
// ####################################################################################################
// ####################################################################################################

{ // Get a list of file paths using the glob function.
    { // parameters
        $ip = getIP();
        $ip_formated = formatIP($ip);
    }
    $search = '_logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR . $ip_formated . DIRECTORY_SEPARATOR . '*.txt';
    // die($search);
    $filename_array = glob($search);
    // die(var_dump($folder_array));
}
// ####################################################################################################

{ // content

    if (sizeof($filename_array) > 0) {
        sort($filename_array);
        $last_log_filename = array_pop($filename_array);
        $result = file_get_contents($last_log_filename);
        $result = utf8_encode($result);
    } else {
        $result = 'Nenhum registro encontrado!';
    }
}

// ####################################################################################################

{//tempo recarregamento
    $t = 5; //segundo
    if(isset($_GET['t'])){       
        $t = intval(trim($_GET['t']));
        $t = $t<3 ? 3 : $t;
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
<title><?php echo getIP(); ?> -> LAST LOGs!</title>
</head>
<body>
<pre><?php echo $result; ?>
		
<a href='<?php echo $last_log_filename; ?>' target='_blank' style='text-decoration:none; color:#000; float:left;' title='ACESSAR O ARQUIVO'>[>]</a>
</pre>
	
	<script type="text/javascript">
    setTimeout(function(){
    	document.location = 'log.php?t=<?php echo $t; ?>';
    },<?php echo $t*1000; ?>);
    </script>
</body>
</html>

















