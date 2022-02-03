<?php

define('BD_PATH_ABS_ANOTACOES', $_SERVER['DOCUMENT_ROOT']."/libs/anotacoes/1/"); /*versao 1*/
define('BD_PATH_ABS_BIB', 	$_SERVER['DOCUMENT_ROOT']."/libs/comuns/2/");

//define('MYSQL_BD_LOCAL', 'lucianoamaral_goepa');
//define('MYSQL_BD_LOCAL', 'ilxbryu3_agrocontrole');
//define('MYSQL_BD_LOCAL', 'lucianoamaral_qualycontrole');
define('MYSQL_BD_LOCAL', 'flaviosousa_senhas');



define('MYSQL_DNS_LOCAL', 'mysql:host=localhost;dbname='.MYSQL_BD_LOCAL.';');
define('MYSQL_USER_LOCAL', 'root');
define('MYSQL_PASSWORD_LOCAL', '');



?>