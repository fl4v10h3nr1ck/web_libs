<?php

header('Content-type: text/html; charset=UTF-8');

chdir(dirname(__FILE__)); 

include_once getcwd().'/VoceNaoVaiPassar.class.php';


$vnvp = new VoceNaoVaiPassar();


	if( array_key_exists( "nome_da_funcao", $_POST) ){

	if (method_exists($vnvp, $_POST["nome_da_funcao"])) 
	call_user_func(array($vnvp, $_POST["nome_da_funcao"]));
	}
	
?>