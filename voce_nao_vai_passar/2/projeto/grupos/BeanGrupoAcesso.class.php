<?php


/** @AnotTabela(nome="grupos_x_acessos", prefixo="gxa", join="inner join acessos as acs on acs.id_acesso=###.fk_acesso") */
final class BeanGrupoAcesso{


/** @AnotCampo(nome="id_grupo_x_acesso", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_acesso", tipo="int") */
public $fk_acesso;

/** @AnotCampo(nome="fk_grupo", tipo="int") */
public $fk_grupo;

/** @AnotCampo(nome="valor") */
public $valor;

/** @AnotCampo(nome="tipo", select_apenas=true, apelido="tipo_acs", sem_prefixo=true) */
public $tipo;

/** @AnotCampo(nome="cod", select_apenas=true, apelido="cod_acs", sem_prefixo=true) */
public $cod_acesso;

}
?>