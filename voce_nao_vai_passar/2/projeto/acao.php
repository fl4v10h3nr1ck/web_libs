<?php
header('Content-type: text/html; charset=UTF-8');

if(!isset($_SESSION))
session_start();


chdir(dirname(__FILE__)); 

	if(array_key_exists( "funcao", $_POST) && 
		array_key_exists( "path", $_POST) &&
			array_key_exists( "classe", $_POST)){
	
		include_once getcwd().$_POST['path'].$_POST['classe'].'.class.php';
		
		$classe = new $_POST['classe'];
		
		if(!is_object($classe)){
			
			echo '{"status":"ERRO", "ERRO":"Parâmetros inválidos (N_CLASSE)."}';
			return;
		}
		
		if (method_exists( $classe, $_POST["funcao"]))
			call_user_func(array( $classe, $_POST["funcao"]));
		else
			echo '{"status":"ERRO", "ERRO":"Parâmetros inválidos (N_FUNCAO)."}';
	}
	else
		echo '{"status":"ERRO", "ERRO":"Parâmetros inválidos (N_POST)."}';



	
?>