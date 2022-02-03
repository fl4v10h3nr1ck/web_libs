<?php


/** @AnotTabela(nome="acessos", prefixo="acs") */
final class BeanAcesso{


/** @AnotCampo(nome="id_acesso", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="nome") */
public $nome;

/** @AnotCampo(nome="cod") */
public $codigo;


/** @AnotCampo(nome="tipo") */
public $tipo;

/** @AnotCampo(nome="ordem", tipo="int") */
public $ordem;


	
	
	

	
	
}
?>