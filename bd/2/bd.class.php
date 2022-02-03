<?php


chdir(dirname(__FILE__)); 

include_once getcwd().'/define.php';


class bd{


private  $con;



	function __construct(){
	
	$this->conecta();
	}



	

	
	final function conecta(){
	
	$this->con = new PDO(MYSQL_DNS_LOCAL, MYSQL_USER_LOCAL, MYSQL_PASSWORD_LOCAL);	
	
	if(!is_object($this->con))
	return false;
	
	//importante para a saída com acentuacao via BD
	$this->con->query("SET NAMES 'utf8'");
	$this->con->query('SET character_set_connection=utf8');
	$this->con->query('SET character_set_client=utf8');
	$this->con->query('SET character_set_results=utf8');
	
	return true;
	}
	
	
	
	
	
	
	final function getReferencia(){
	
	return $this->con;
	}
	
	
	
	
	
	
	
}
?>