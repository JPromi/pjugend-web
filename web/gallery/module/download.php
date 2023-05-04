<?php
ignore_user_abort ();
set_time_limit ( 0 );

$file_path = '../../../cdn/gallery/tmp/'.$_GET["tmp"];
$file_type = 'application/zip';
$file_name = $_GET["tmp"];

header ( 'Cache-Control: max-age=31536000' );
header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header ( 'Content-Length: ' . filesize ( $file_path ) );
header ( 'Content-Disposition: filename="' . $file_name . '"' );
header ( 'Content-Type: ' . $file_type . '; name="' . $file_name . '"' );
readfile ( $file_path );
unlink ( $file_path );

exit ();
?>