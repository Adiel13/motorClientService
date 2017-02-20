<?php
$inipath = php_ini_loaded_file();
ini_set('upload_max_filesize', '128M');

$file_path = "/var/www/html/";
$file_path = $file_path . basename($_FILES['uploaded_file']['name']);
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$file_path))	{
		error_log ("success");
	}else {
		error_log ( "error");
	}

error_log(print_r($_FILES, true));
error_log(ini_get('upload_max_filesize'));
error_log($inipath);

?>