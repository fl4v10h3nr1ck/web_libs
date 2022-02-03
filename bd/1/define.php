<?php

define('BD_PATH_ABS_ANOTACOES', $_SERVER['DOCUMENT_ROOT']."/libs/anotacoes/1/"); /*versao 1*/
define('BD_PATH_ABS_BIB', 	$_SERVER['DOCUMENT_ROOT']."/libs/comuns/2/");

//define('MYSQL_BD_LOCAL', 'agenda_full_web');
//define('MYSQL_BD_LOCAL', 'icart_bd');
//define('MYSQL_BD_LOCAL', 'appbhcom_mototaxi');
//define('MYSQL_BD_LOCAL', 'appeasyb_easy_business_bd');
//define('MYSQL_BD_LOCAL', 'super_buska_web');
//define('MYSQL_BD_LOCAL', 'vnvp');
define('MYSQL_BD_LOCAL', 'bd_personale');
//define('MYSQL_BD_LOCAL', 'bd_gaia');
//define('MYSQL_BD_LOCAL', 'agrocontrole');
//define('MYSQL_BD_LOCAL', 'msc_bd');
//define('MYSQL_BD_LOCAL', 'astal');
//define('MYSQL_BD_LOCAL', 'tudoautomotivo');
//define('MYSQL_BD_LOCAL', 'bd_personale_teste');
//define('MYSQL_BD_LOCAL', 'ingressos');
//define('MYSQL_BD_LOCAL', 'lucianoamaral_qualycontrole');

define('MYSQL_DNS_LOCAL', 'mysql:host=localhost;dbname='.MYSQL_BD_LOCAL.';');
define('MYSQL_USER_LOCAL', 'root');
define('MYSQL_PASSWORD_LOCAL', '');



?>