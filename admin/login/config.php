<?php

define('BASE_URL', 
(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
. '://' . $_SERVER['HTTP_HOST']
. rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\')
);

?>