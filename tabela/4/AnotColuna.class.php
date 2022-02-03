<?php


final class AnotColuna extends Annotation{



public $rotulo;
public $posicao;
public $comprimento;
public $func_composicao;
public $alinhamento;
public $nao_pesquisar;
public $como_data;

/* 	se true: caso o campo seja null ou vazio, retorna somente os itens que possuem o campo null ou vazio no banco (is null ou ='')
	se false ou null (padrao): caso o campo seja null ou vazio, retorna like %% (todos os campos, ignora o filtro)
*/   
public $pesquisar_null_ou_vazio;
}
?>