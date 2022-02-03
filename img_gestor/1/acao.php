<?php

header('Content-type: text/html; charset=UTF-8');

chdir(dirname(__FILE__)); 

include_once getcwd().'/ImgGestor.class.php';


$imggestor = new ImgGestor();

call_user_func(array($imggestor, "salvarImg"));

	
?>