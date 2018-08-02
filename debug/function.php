<?php
function debug($value) {
	echo "<pre>";
	var_dump($value);
	echo "</pre>";
}

function debugInFile($value, $filename)
{
	ob_start();
	debug($value);
	$str_value = ob_get_clean();
	//$str_value = json_encode($value);


	$f = fopen($filename, 'a');
	fwrite($f, $str_value);
	fclose($f);
}
