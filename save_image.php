<?php
if(strpos($_GET['url'],'https://')!==false){
	$url = explode('/',$_GET['url']);
	$extension = explode('.',$url[count($url)-1]);
	if($extension[count($extension)-1]=='jpg' || $extension[count($extension)-1]=='jpeg' || $extension[count($extension)-1]=='gif' || $extension[count($extension)-1]=='png'){
		file_put_contents($_GET['root'].'/'.$url[count($url)-1],file_get_contents($_GET['url']));
		echo $url[count($url)-1];
	}
}
?>