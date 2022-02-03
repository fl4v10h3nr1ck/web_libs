<?php


/** @AnotTabela(nome="departamentos", prefixo="dpt", join="inner join filiais as fll on fll.id_filial=###.fk_filial left outer join usuarios as user on ###.fk_chefe=user.id_usuario") */
final class BeanDepartamento{


/** @AnotCampo(nome="id_departamento", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_filial", tipo="int") */
public $fk_filial;

/** @AnotColuna(rotulo="Código", posicao=1, comprimento=15, alinhamento="center") 
	@AnotCampo(nome="codigo") */
public $codigo;

/** @AnotColuna(rotulo="Nome", posicao=2, comprimento=30) 
	@AnotCampo(nome="nome") */
public $nome;

/** @AnotColuna(rotulo="Filial", posicao=3, comprimento=20, alinhamento="center") 
	@AnotCampo(nome="fll.nome", select_apenas=true, apelido="nome_filial", sem_prefixo=true) */
public $nome_filial;

/** @AnotColuna(rotulo="Situação", posicao=4, comprimento=15, alinhamento="center", func_composicao="statusFormatado", nao_pesquisar=true) 
	@AnotCampo(nome="status", tipo="int") */
public $status;

/** @AnotCampo(nome="fk_chefe", tipo="int") */
public $fk_chefe;

/** @AnotColuna(rotulo="Diretor(a)", posicao=5, comprimento=20, alinhamento="center", nao_pesquisar=true) 
	@AnotCampo(nome="user.usuario", select_apenas=true, apelido="nome_direcao", sem_prefixo=true) */
public $nome_direcao;



	
	
	

	public function statusFormatado(){
		
		if(array_key_exists('PODE_EDITAR', $_SESSION) && $_SESSION['PODE_EDITAR'])
			return '
			<input  '.($this->status>0?"checked":"").' id="departamento_status_'.$this->id.'" class="switch switch--shadow" type="checkbox" onChange="javascript:ativarDesativar()">
			<label for="departamento_status_'.$this->id.'"></label>';
			
		return "<span style='color:".($this->status>0?"green'>":"red'>IN")."ATIVO</span>";		
	}

	
	
	
	
}
?>