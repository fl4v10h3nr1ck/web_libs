<?php


/** @AnotTabela(nome="usuarios", prefixo="us") */
final class BeanUsuario{



/** @AnotCampo(nome="id_usuario", tipo="int", ehId=true) */
public $id;

/** @AnotColuna(rotulo="Nome", posicao=1, comprimento=30, alinhamento="left") 
	@AnotCampo(nome="usuario") */
public $usuario;

/** @AnotCampo(nome="identificacao") */
public $identificacao;

/** @AnotColuna(rotulo="E-mail", posicao=2, comprimento=25, alinhamento="left") 
	@AnotCampo(nome="email") */
public $email;

/** @AnotCampo(nome="senha") */
public $senha;

/** @AnotColuna(rotulo="Situação", posicao=3, comprimento=15, alinhamento="center", func_composicao="statusFormatado", nao_pesquisar=true) 
	@AnotCampo(nome="status", tipo="int") */
public $status;

/** @AnotColuna(rotulo="Cadastro", posicao=5, comprimento=15, alinhamento="center") 
	@AnotCampo(nome="data_cadastro", tipo="data") */
public $data_cadastro;

/** @AnotCampo(nome="nome_completo") */
public $nome_completo;

/** @AnotColuna(rotulo="Fone", posicao=4, comprimento=15, alinhamento="center", nao_pesquisar=true) 
	@AnotCampo(nome="tel") */
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





	public function statusFormatado(){
		
	if($this->status>0)
	return "<font color=green>ATIVO</font>";
	else
	return "<font color=red>INATIVO</font>";
	}




}
?>