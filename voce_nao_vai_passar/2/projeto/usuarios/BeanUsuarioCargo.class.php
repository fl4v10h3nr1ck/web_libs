<?php


/** @AnotTabela(nome="usuarios_x_cargos", prefixo="uxc", join="inner join cargos as crg on ###.fk_cargo=crg.id_cargo inner join departamentos as dpt on dpt.id_departamento=crg.fk_departamento inner join filiais as fll on fll.id_filial=dpt.fk_filial") */
final class BeanUsuarioCargo{


/** @AnotCampo(nome="id_usuario_cargo", tipo="int", ehId=true) */
public $id;

/** @AnotCampo(nome="fk_usuario", tipo="int") */
public $fk_usuario;

/** @AnotCampo(nome="fk_cargo", tipo="int") */
public $fk_cargo;

/** @AnotCampo(nome="crg.nome", select_apenas=true, apelido="nome_cargo", sem_prefixo=true) */
public $nome_cargo;

/** @AnotCampo(nome="dpt.nome", select_apenas=true, apelido="nome_departamento", sem_prefixo=true) */
public $nome_departamento;

/** @AnotCampo(nome="fll.nome", select_apenas=true, apelido="nome_filial", sem_prefixo=true) */
public $nome_filial;

/** @AnotCampo(nome="fll.id_filial", select_apenas=true, apelido="id_filial", sem_prefixo=true) */
public $id_filial;

/** @AnotCampo(nome="dpt.id_departamento", select_apenas=true, apelido="fk_departamento", sem_prefixo=true) */
public $fk_departamento;

/** @AnotCampo(nome="padrao", tipo="int") */
public $padrao;
}
?>