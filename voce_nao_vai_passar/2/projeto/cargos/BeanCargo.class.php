<?php


/** @AnotTabela(nome="cargos", prefixo="crg", join="inner join departamentos as dpt on dpt.id_departamento=###.fk_departamento inner join filiais as fll on fll.id_filial=dpt.fk_filial") */
final class BeanCargo{


/** @AnotCampo(nome="id_cargo", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_departamento", tipo="int") */
public $fk_departamento;

/** @AnotColuna(rotulo="Código", posicao=1, comprimento=15, alinhamento="center") 
	@AnotCampo(nome="codigo") */
public $codigo;

/** @AnotColuna(rotulo="Nome", posicao=2, comprimento=30) 
	@AnotCampo(nome="nome") */
public $nome;

/** @AnotColuna(rotulo="Departamento", posicao=3, comprimento=20, alinhamento="center") 
	@AnotCampo(nome="dpt.nome", select_apenas=true, apelido="nome_departamento", sem_prefixo=true) */
public $nome_departamento;

/** @AnotCampo(nome="dpt.codigo", select_apenas=true, apelido="codigo_departamento", sem_prefixo=true) */
public $codigo_departamento;
	

/** @AnotColuna(rotulo="Filial", posicao=4, comprimento=20, alinhamento="center") 
	@AnotCampo(nome="fll.nome", select_apenas=true, apelido="nome_filial", sem_prefixo=true) */
public $nome_filial;	
	
/** @AnotColuna(rotulo="Situação", posicao=5, comprimento=15, alinhamento="center", func_composicao="statusFormatado", nao_pesquisar=true) 
	@AnotCampo(nome="status", tipo="int") */
public $status;

/** @AnotCampo(nome="dpt.fk_filial", tipo="int", select_apenas=true, apelido="id_filial_departamento", sem_prefixo=true) */
public $id_filial_departamento;





	public function statusFormatado(){
		
		if(array_key_exists('PODE_EDITAR', $_SESSION) && $_SESSION['PODE_EDITAR'])
			return '
			<input  '.($this->status>0?"checked":"").' id="cargo_status_'.$this->id.'" class="switch switch--shadow" type="checkbox" onChange="javascript:ativarDesativar()">
			<label for="cargo_status_'.$this->id.'"></label>';
			
		return "<span style='color:".($this->status>0?"green'>":"red'>IN")."ATIVO</span>";		
	}

	
	
	
	
}
?>