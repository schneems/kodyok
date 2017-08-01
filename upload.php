<?php
$input = fopen("php://input", "r");
$temp = tmpfile();
$realSize = stream_copy_to_stream($input, $temp);
fclose($input);

$save_name = $_GET["qqfile"];

$extension = explode('.',$save_name);
if($extension[count($extension)-1]=='jpg' || $extension[count($extension)-1]=='jpeg' || $extension[count($extension)-1]=='gif' || $extension[count($extension)-1]=='png'){
	$target = fopen($_GET['root']."/".$save_name, "w");
	fseek($temp, 0, SEEK_SET);
	stream_copy_to_stream($temp, $target);
	fclose($target);
}
?>