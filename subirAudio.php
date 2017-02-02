<?php

$file_path = "/var/www/html/";
$file_path = $file_path . basename($_FILES['uploaded_file']['name']);
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$file_path))	{
		error_log ("success");
	}else {
		error_log ( "error");
	}

?>