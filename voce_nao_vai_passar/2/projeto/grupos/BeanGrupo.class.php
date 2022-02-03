<?php

chdir(dirname(__FILE__)); 

chdir('../');

include_once getcwd().'/define.php';



/** @AnotTabela(nome="grupos", prefixo="gp", join="inner join filiais as fll on fll.id_filial=###.fk_filial") */
final class BeanGrupo{



/** @AnotCampo(nome="id_grupo", tipo="int", ehId=true) */
public $id_grupo;

/** @AnotColuna(rotulo="Nome", posicao=2, comprimento=20, alinhamento="center") 
	@AnotCampo(nome="nome") */
public $nome;

/** @AnotColuna(rotulo="Código", posicao=1, comprimento=15, alinhamento="center") 
	@AnotCampo(nome="cod") */
public $codigo;


/** @AnotColuna(rotulo="Filial", posicao=3, comprimento=15, alinhamento="center") 
	@AnotCampo(nome="fll.nome", select_apenas=true, apelido="nome_filial", sem_prefixo=true) */
public $nome_filial;

/** @AnotColuna(rotulo="Descrição", posicao=4, comprimento=30, alinhamento="left", nao_pesquisar=true) 
	@AnotCampo(nome="descricao") */
public $descricao;

/** @AnotCampo(nome="data_criacao", tipo="data") */
public $data_criacao;

/** @AnotColuna(rotulo="Status", posicao=5, comprimento=10, alinhamento="center", func_composicao="formataStatus") 
	@AnotCampo(nome="status", tipo="int") */
public $status;

/** @AnotColuna(rotulo="ADMIN", posicao=6, comprimento=10, alinhamento="center", nao_pesquisar=true, func_composicao="formataAdmin") 
	@AnotCampo(nome="admin", tipo="int") */
public $admin;

/** @AnotCampo(nome="fk_filial", tipo="int") */
public $fk_filial;



	public function formataStatus(){
		
		if(array_key_exists('PODE_EDITAR', $_SESSION) && $_SESSION['PODE_EDITAR'])
			return '
				<input  '.($this->status>0?"checked":"").' id="grupo_status_'.$this->id_grupo.'" class="switch switch--shadow" type="checkbox" onChange="javascript:ativarDesativar()">
				<label for="grupo_status_'.$this->id_grupo.'"></label>';
		
		
		return "<span style='color:".($this->status>0?"green'>":"red'>IN")."ATIVO</span>";	
	}

	
	
	
	
	public function formataAdmin(){
		
		if($this->admin<=0)
			return "<img src='".VNVP_PATH_SMP."imgs/check_nao.png' class='check'>";

		return "<img src='".VNVP_PATH_SMP."imgs/check_sim.png' class='check'>";
	}

	
	
}
?>