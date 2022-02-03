<?php


/** @AnotTabela(nome="filiais", prefixo="fll") */
final class BeanFilial{


/** @AnotCampo(nome="id_filial", tipo="int", ehId=true) */
public $id;

/** @AnotColuna(rotulo="Código", posicao=1, comprimento=20, alinhamento="center") 
	@AnotCampo(nome="codigo") */
public $codigo;

/** @AnotColuna(rotulo="Nome", posicao=2, comprimento=60, alinhamento="left") 
	@AnotCampo(nome="nome") */
public $nome;

/** @AnotCampo(nome="endereco") */
public $endereco;

/** @AnotCampo(nome="tel_1") */
public $tel_1;

/** @AnotCampo(nome="tel_2") */
public $tel_2;

/** @AnotCampo(nome="email") */
public $email;

/** @AnotCampo(nome="site") */
public $site;

/** @AnotColuna(rotulo="Situação", posicao=3, comprimento=20, alinhamento="center", func_composicao="formataStatus") 
	@AnotCampo(nome="status", tipo="int") */
public $status;

	






	public function formataStatus(){
		
		if(array_key_exists('PODE_EDITAR', $_SESSION) && $_SESSION['PODE_EDITAR'])
			return "
			<input  ".($this->status>0?"checked":"")." id='filial_status_".$this->id."' class='switch switch--shadow' type='checkbox' onChange='javascript:ativarDesativar()'>
			<label for='filial_status_".$this->id."'></label>";
			
		return "<span style='color:".($this->status>0?"green'>":"red'>IN")."ATIVO</span>";		
	}

	
	
	
	

	
	
}
?>