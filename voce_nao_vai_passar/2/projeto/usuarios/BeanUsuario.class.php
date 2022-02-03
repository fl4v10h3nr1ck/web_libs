<?php


/** @AnotTabela(nome="usuarios", prefixo="us") */
final class BeanUsuario{


/** @AnotCampo(nome="id_usuario", tipo="int", ehId=true) */
public $id;

/** @AnotColuna(rotulo="Usuário", posicao=1, comprimento=20, alinhamento="left") 
	@AnotCampo(nome="usuario") */
public $usuario;

/** @AnotCampo(nome="identificacao") */
public $identificacao;

/** @AnotColuna(rotulo="E-mail", posicao=3, comprimento=25, alinhamento="left") 
	@AnotCampo(nome="email") */
public $email;

/** @AnotCampo(nome="senha") */
public $senha;

/** @AnotCampo(nome="data_cadastro", tipo="data") */
public $data_cadastro;

/** @AnotColuna(rotulo="Nome Completo", posicao=2, comprimento=25, alinhamento="left") 
	@AnotCampo(nome="nome_completo") */
public $nome_completo;

/** @AnotCampo(nome="tel") */
public $tel;

/** @AnotCampo(nome="cel") */
public $cel;

/** @AnotCampo(nome="logradouro") */
public $logradouro;
	
/** @AnotCampo(nome="num_residencia") */
public $num_residencia;

/** @AnotCampo(nome="cidade") */
public $cidade;

/** @AnotCampo(nome="uf") */
public $uf;

/** @AnotCampo(nome="bairro") */
public $bairro;

/** @AnotCampo(nome="cep") */
public $cep;

/** @AnotCampo(nome="complemento") */
public $complemento;

/** @AnotCampo(nome="token") */
public $token;

/** @AnotCampo(nome="img_profile") */
public $img_profile;

/** @AnotCampo(nome="outras_infos") */
public $outras_infos;

/** @AnotCampo(nome="conectado", tipo="int") */
public $conectado;

/** @AnotColuna(rotulo="Situação", posicao=4, comprimento=20, alinhamento="center", func_composicao="statusFormatado", nao_pesquisar=true) 
	@AnotCampo(nome="status", tipo="int") */
public $status;

public $dev;

public $id_filial_atual;






	public function statusFormatado(){
		
		if(array_key_exists('PODE_EDITAR', $_SESSION) && $_SESSION['PODE_EDITAR'])
			return '
			<input  '.($this->status>0?"checked":"").' id="usuario_status_'.$this->id.'" class="switch switch--shadow" type="checkbox" onChange="javascript:ativarDesativar()">
			<label for="usuario_status_'.$this->id.'"></label>';
			
		return "<span style='color:".($this->status>0?"green'>":"red'>IN")."ATIVO</span>";	
	}

	



}
?>