<?php


/** @AnotTabela(nome="grupos", prefixo="gp") */
final class BeanGrupo{



/** @AnotCampo(nome="id_grupo", tipo="int", ehId=true) */
public $id_grupo;

/** @AnotColuna(rotulo="Código", posicao=1, comprimento=20, alinhamento="center") 
	@AnotCampo(nome="cod") */
public $cod;

/** @AnotColuna(rotulo="Nome", posicao=2, comprimento=30, alinhamento="center") 
	@AnotCampo(nome="nome") */
public $nome;


/** @AnotColuna(rotulo="Descrição", posicao=3, comprimento=50, alinhamento="left") 
	@AnotCampo(nome="descricao") */
public $descricao;

/** @AnotCampo(nome="data_criacao", tipo="data") */
public $data_criacao;




}
?>