<?php


/** @AnotTabela(nome="usuarios_x_grupos", prefixo="uxg") */
final class BeanUsuarioGrupo{


/** @AnotCampo(nome="id_usuario_x_grupo", tipo="int", ehId=true) */
public $id_usuario_x_grupo;

/** @AnotCampo(nome="fk_usuario", tipo="int") */
public $fk_usuario;

/** @AnotCampo(nome="fk_grupo", tipo="int") */
public $fk_grupo;






}
?>