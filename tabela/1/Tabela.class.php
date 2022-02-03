<?php

if(!isset($_SESSION))
session_start();
 
 
chdir(dirname(__FILE__)); 

include_once getcwd().'/define.php';

include_once TAB_LOCAL_ABS_ANOTACOES.'annotations.php';

include_once TAB_BASE_PACK_ABS.'AnotColuna.class.php';

include_once TAB_LOCAL_ABS_BD.'BdUtil.class.php';




	function ordenador($a, $b){
		return ($a['posicao']==$b['posicao']?0:($a['posicao']<$b['posicao']?-1:1));
	}
	

	

final class Tabela{


private $bd;
private $path_abs_objeto;
private $where;
private $orderby;
private $mostrar_opcoes_pesquisa;
private $func_pos_selecionar;
private $func_pos_pesquisar;
private $func_where_dinamica;
private $func_orderby_dinamica;
private $func_duplo_click;
private $num_max_linhas;
private $mostrar_paginacao;
private $scroll_com_controles;




	function __construct($id_tab=null) {

	if($id_tab==null)
	$_SESSION["tab_objeto"] =null;
	
	$this->bd = new BdUtil();	
	
	$this->setPathABSDoObjeto("");
	
	$this->setWhereFixo("");
	
	$this->setOrderByFixo("");
	
	$this->setMostrarOpcoesDePesquisa();
	
	$this->setMostrarPaginacao();
	
	$this->setMostrarPaginacao();
	
	$this->setNumMaxDeLinhas();
	
	$this->setTipoDeScrollComControles();
	}


	

	
	
	
	public function dependencias(){
	
	echo "
	
	<script src='".TAB_BASE_PACK_SPS."tabela.js' type='text/javascript'></script>
	
	<link rel='stylesheet' href='".TAB_BASE_PACK_SPS."tabela.css' type='text/css' media='all'>";
	}

	
	
	
	
	public function setPathABSDoObjeto($path){
		
	$this->path_abs_objeto= $path;	
	}
	
	
	
	
	
	public function setWhereFixo($where){
		
	$this->where= $where;	
	}
	
	
	
	
	
	public function setOrderByFixo($orderby){
		
	$this->orderby= $orderby;	
	}
	
	
	
	
	
	public function setFuncaoPosSelecionar($funcao){
		
	$this->func_pos_selecionar= $funcao;	
	}
	
	
	
	
	
	public function setFuncaoPosPesquisar($funcao){
		
	$this->func_pos_pesquisar= $funcao;	
	}
	
	
	
	
	public function setFuncaoWhereDinamica($funcao){
		
	$this->func_where_dinamica= $funcao;	
	}
	
	
	
	
	
	
	public function setFuncaoOrderByDinamica($funcao){
		
	$this->func_orderby_dinamica= $funcao;	
	}
	
	
	
	
	
	
	public function setFuncaoDuploClick($funcao){
		
	$this->func_duplo_click=  $funcao;	
	}
	
	
	
	
	
	public function setMostrarOpcoesDePesquisa($mostrar = true){
		
	$this->mostrar_opcoes_pesquisa= $mostrar;	
	}
	
	
	
	
	
	
	public function setNumMaxDeLinhas($num_max = 15){
		
	$this->num_max_linhas= $num_max;	
	}
	
	
	
	
	
	
	public function setMostrarPaginacao($mostrar_paginacao = true){
		
	$this->mostrar_paginacao= $mostrar_paginacao;	
	}
	
	
	
	
	
	
	public function setTipoDeScrollComControles($controle = false){
		
	$this->scroll_com_controles= $controle;	
	}
	
	
	

	
		
	
	public function getTabela($objeto, $nome_campo_id){
	
	if(!is_object($objeto))
	return;
	
	$metaDados = $this->getMetaDadosDeCabecalho($objeto);
	
	if($metaDados==null)
	return;

	$id_tab=rand(0, 999999); 
	
	if(!array_key_exists("tab_objeto", $_SESSION) || $_SESSION["tab_objeto"]==null)
	$_SESSION["tab_objeto"] = array();
	
	$_SESSION["tab_objeto"][]=array($id_tab=>serialize($objeto), 
										"path"=>$this->path_abs_objeto,
											"nome_campo_id"=>$nome_campo_id, 										
												"nome_classe"=>get_class($objeto),
													"where"=>strlen($this->where)==0?"":$this->where,
														"orderby"=>strlen($this->orderby)==0?"":$this->orderby,
															"paginar"=>$this->mostrar_paginacao?$this->num_max_linhas:0);
	

	$form = "
	<div class='tab_local' align ='center'>
	<input type='hidden' id='".$nome_campo_id."' value='".$id_tab."'>
	<input type='hidden' id='tab__path_".$id_tab."' value='".TAB_BASE_PACK_SPS."'>
	<input type='hidden' id='func_pos_selecionar_".$id_tab."' value='".$this->func_pos_selecionar."'>
	<input type='hidden' id='func_pos_pesquisar_".$id_tab."' value='".$this->func_pos_pesquisar."'>
	<input type='hidden' id='func_where_dinamica_".$id_tab."' value='".$this->func_where_dinamica."'>
	<input type='hidden' id='func_orderby_dinamica_".$id_tab."' value='".$this->func_orderby_dinamica."'>
	<input type='hidden' id='func_duploclique_".$id_tab."' value='".$this->func_duplo_click."'>";
	 
	 
	 
		if($this->mostrar_opcoes_pesquisa){
		
		$form .= "
		<div class='tab_area_pesquisa'>
			<div class='area_termo_pesquisa' align='left'>
			Pesquisar por:<br>
			<input type='text' id='tab_termos_".$id_tab."' class='tab_termos' onKeyPress=\"if ((window.event ? event.keyCode : event.which) == 13) { pesquisar('".$nome_campo_id."'); }\">
			</div>
			<div class='area_local_pesquisa' align='left'>
			Pesquisar em:<br>
				<select id='tab_local_".$id_tab."' class='tab_termos' onChange=\"pesquisar('".$nome_campo_id."')\">";
			
			foreach($metaDados as $valor){
			
			if($valor['pesquisar']===true)
			$form .= "
					<option value='".$valor['posicao']."'>".$valor['rotulo']."</option>";	
			}	


				
		$form .= "
				</select>		
			</div>
			<div class='area_bt_pesquisar' align='left'>
					<div class='tab_bt' id='tab_bt_pesquisar_".$id_tab."' onclick='javascript:pesquisar(\"".$nome_campo_id."\")'>Pesquisar</div>	
			</div>
			<div style='clear:both'></div>
		</div>";
			
		}
		
	$form .= "
		<div class='area_tab_geral'>
			<div id='tab_carregamento_".$id_tab."' class='tab_carregamento'>
			<img src='".TAB_BASE_PACK_SPS."imgs/load.gif'>
			</div>

			".($this->scroll_com_controles?$this->controladorRolagem($id_tab):"")."
			
			<div align='center' class='area_conteudo_tab ".$nome_campo_id."_div' ".($this->scroll_com_controles?"style='overflow:hidden'":"").">
				<table border= '1'  id ='area_conteudo_tab_".$id_tab."' class = 'tab_conteudo ".$nome_campo_id."_tab'  align ='center' cellspacing ='1'>
					<tr class='linha_cabecalho'>";
		
	foreach($metaDados as $valor)	
	$form .= "		<th width='".$valor['comprimento']."%' align='center'>".$valor['rotulo']."</th>";
	
	
	$tab = $this->getValores($id_tab);
	
	$rotulo  = null;
		
		if(strpos($tab, "##_@_##")!==false){
		
		$tab = explode("##_@_##", $tab);
		
		$form .= "
					</tr>
					".$tab[0]."
				</table>";
		
		$tab = explode("_", $tab[1]);
		
		$form .= "<input type='hidden' id='pag_index_".$id_tab."' value='0'>";
	
		$rotulo = $tab[0];
	
		}
		else
		$form .= "
					</tr>
					".$tab."
				</table>";

	
	$form .= "
			</div>";
			
	if($this->mostrar_paginacao)		
	$form .= "
			<div id='".$nome_campo_id."_paginacao' align='center' class='paginacao'>
				<table>
					<tr>
						<td>
						<div class='tab_bt' id='tab_anterior_".$id_tab."' onclick='javascript:pesquisar(\"".$nome_campo_id."\", -1)' style='margin:5px 0px 5px 0px'>Anterior</div>	
						</td>
						<td align='center' id='rot_pag_".$id_tab."'>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$rotulo."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
						<td>
						<div class='tab_bt' id='tab_proximo_".$id_tab."' onclick='javascript:pesquisar(\"".$nome_campo_id."\", 1)' style='margin:5px 0px 5px 0px'>Próximo</div>	
						</td>
					</tr>
				</table>
			</div>";		
	

	$form .= "		
		</div>
	</div>";
	
	
	return $form;
	}
	
	
	
	
	
	
	
	public function pesquisar($id_tab){
	
	echo $this->getValores($id_tab);
	}
	
	



	
	
	
	
	private function getValores($id_tab){

	$objeto = null;
	$where = null;
	$orderby = ""; 
	
	$nome_campo_id = null; 
	
	$num_max = 0;
	
	
		if(count($_SESSION["tab_objeto"])>0){
			
			foreach($_SESSION["tab_objeto"] as $item){
			
				if(count($item)>0 && array_key_exists($id_tab, $item)){

				include_once $item['path'].$item['nome_classe'].".class.php";
				
				$objeto = unserialize($item[$id_tab]);
				$where = $item['where'];
				$orderby = $item['orderby'];
				
				$nome_campo_id = $item['nome_campo_id'];
				
				$num_max = $item['paginar'];
				
				break;
				}	
			}
		}
		else
		return $this->erro("COD: 001");

	
	if(!is_object($objeto))
	return $this->erro("COD: 002");
	
	$metadados = $this->getMetaDadosDePesquisa($objeto);
	
	if($metadados==null)
	return $this->erro();


	$query_where  = (strlen($where)>0?$where:"");
	
	if(array_key_exists("where", $_POST))
	$query_where .= (strlen($query_where)>0 && strlen($_POST['where'])>0?" AND ": "").$_POST['where'];
			
	$query_where .= (strlen($query_where)>0?" AND ": "").$this->getQueryTermos($objeto, $metadados);

	
	if(array_key_exists("order", $_POST))
	$orderby .= (strlen($orderby)>0 && strlen($_POST['order'])>0?", ": "").$_POST['order'];

	
	$index_atual = array_key_exists("index", $_POST)?$_POST['index']:0;
	$rotulo = "";
	
	$reg  = array();
	
		if($num_max>0){
		
		$cont_total = $this->bd->cont($objeto, null, $query_where, $orderby);	
		
			if($cont_total>0){
			
			$tipo = array_key_exists("tipo", $_POST)?$_POST['tipo']:0;
			
				if($tipo>0){
				
				if($index_atual+$num_max<=$cont_total)
				$index_atual += $num_max;
				}
				elseif($tipo<0){
				
				if($index_atual-$num_max>=0)
				$index_atual -= $num_max;
				}
					
			$reg = $this->bd->getPorQuery($objeto, null, $query_where, $orderby, $index_atual.", ".$num_max);
			
			$rotulo = ($index_atual+1)." à ".($index_atual+count($reg))." de ".$cont_total;
			}	
		}
		else
		$reg = $this->bd->getPorQuery($objeto, null, $query_where, $orderby);	
		
	
	$resultado = "";
	
		if(count($reg)>0){
			
			foreach($reg as $bean){
			
			$id = $this->bd->getValorDeCampoId($bean);
			
			
			$resultado .="<tr class='".$id_tab."' id='".$id_tab."_".$id."' onclick='javascript:seleciona(".$id_tab.", ".$id.")' ondblclick='javascript:executaDuploClick(".$id_tab.")'>";
			
				foreach($metadados['props'] as $meta){
				
			
				$resultado .="<td width='".$meta['comprimento']."%' align='".$meta['alinhamento']."'>".(strlen($meta['func_composicao'])>0?$bean->$meta['func_composicao']():$bean->$meta['nome_prop'])."</td>";
				
				
				}
				
			$resultado .="</tr>";
			}	
		}
		else{
			
		$resultado .="<tr><td colspan='".count($metadados['props'])."' align='center' style='color:red;padding:100px 0px 100px 0px'>&laquo; Nada Encontrado &raquo;</td></tr>";	
		}
	
	
	return $resultado.($num_max>0?"##_@_##".$rotulo."_".$index_atual:"");
	}
	
	
	
	
	
	


	
	private function getQueryTermos($objeto, $metadados){
	
	if( !array_key_exists("local", $_POST)) 
	return " 1 ";
	
	$termos = array_key_exists("termos", $_POST)?$_POST["termos"]:"";
	
	$local = "";
	$tipo = "";
	
		foreach($metadados['props'] as $meta){
			
			if($meta['posicao'] == $_POST['local']){
				
			$local = $this->bd->getPrefixoDeProp($objeto, $meta['nome_prop']).".".$this->bd->getCampoDeProp($objeto, $meta['nome_prop']);
			$tipo = $this->bd->getTipoDeProp($objeto, $meta['nome_prop']);
			break;		
			}	
		}
	
	
	
	if( strlen( $termos ) == 0 || strlen($local)==0) 
	return " 1 ";
	
	
	if(strcmp($tipo, "data")==0)
	$termos = str_replace("/", " ", $termos);
	
	$tokens_do_termo = explode(' ', $termos);
	
	$query_termo = " (";
		
		foreach($tokens_do_termo as $j => $value){
			
		$query_termo .= $local. " like '%".$value."%'";
			
		if( $j < count($tokens_do_termo) -1)
		$query_termo .= " AND ";
		}
		
	$query_termo .= ")";
		
	
	return $query_termo;	
	}
	
	
	
	
	
	


	
	private function getMetaDadosDeCabecalho($objeto){
	
	$reflexao_classe = new ReflectionClass($objeto);
	
	$props = $reflexao_classe->getProperties(ReflectionProperty::IS_PUBLIC | 
												ReflectionProperty::IS_PROTECTED);
	
	if(count($props)==0)
	return null;			
	
	
	$metaDados = array();
	
		foreach($props as $prop){
		
		$reflexao_prop = new ReflectionAnnotatedProperty($objeto, $prop->getName());
	
			if($reflexao_prop!=null){
			
			
			if(!$reflexao_prop->hasAnnotation('AnotColuna'))
			continue;
		
			$metaDados[] = array(
								 "rotulo"=>$reflexao_prop->getAnnotation('AnotColuna')->rotulo, 
								 "posicao"=>$reflexao_prop->getAnnotation('AnotColuna')->posicao, 
								 "comprimento"=>$reflexao_prop->getAnnotation('AnotColuna')->comprimento,
								 "pesquisar"=>$reflexao_prop->getAnnotation('AnotColuna')->nao_pesquisar?false:true);
			}
		}
	
		if($metaDados!=null && count($metaDados)>0){
		
		usort($metaDados, "ordenador"); 

		return $metaDados;
		}
		
	return null;
	}
	
	
	
	
	
	
	
	private function getMetaDadosDePesquisa($objeto){
	
	$anot_classe = new ReflectionAnnotatedClass($objeto);
	
	$reflexao_classe = new ReflectionClass($objeto);
	
	$props = $reflexao_classe->getProperties(ReflectionProperty::IS_PUBLIC | 
												ReflectionProperty::IS_PROTECTED);
	
	if(count($props)==0)
	return null;			
	
	
	$metadados['props'] = array();
	
		foreach($props as $prop){
		
		$reflexao_prop = new ReflectionAnnotatedProperty($objeto, $prop->getName());
	
			if($reflexao_prop!=null){
			
			if(!$reflexao_prop->hasAnnotation('AnotColuna'))
			continue;
			
			$metadados['props'][] = array(
								 "posicao"=>$reflexao_prop->getAnnotation('AnotColuna')->posicao, 
								 "comprimento"=>$reflexao_prop->getAnnotation('AnotColuna')->comprimento, 
								 "alinhamento"=>($reflexao_prop->getAnnotation('AnotColuna')->alinhamento!=null?$reflexao_prop->getAnnotation('AnotColuna')->alinhamento:"center"),
								 "func_composicao"=>($reflexao_prop->getAnnotation('AnotColuna')->func_composicao!=null?$reflexao_prop->getAnnotation('AnotColuna')->func_composicao:null),
								 "nome_prop"=>$prop->getName());
			}
		}
	
	
		if($metadados!=null && count($metadados)>0 && count($metadados['props'])>0){
		
		usort($metadados['props'], "ordenador"); 

		return $metadados;
		}
		
	return null;
	}
	
	
	
	
	
	
	
	
	private function erro($cod=""){
		
	return "erro (".$cod.")";	
	}
	
	
	

	
	
	
	private function controladorRolagem($id_tab){
		
	$form = "
			<input type='hidden' value='0' id='cont_controlador_".$id_tab."'>
			<div class='controlador' align='center' id='controlador_esquerda' onclick='javascript:rolagem(\"".$id_tab."\", -1)'>
			<img src='".TAB_BASE_PACK_SPS."imgs/esquerda.png' class='controlador_seta'>
			</div>
			<div class='controlador' align='center' id='controlador_direita' onclick='javascript:rolagem(\"".$id_tab."\", 1)'>
			<img src='".TAB_BASE_PACK_SPS."imgs/direita.png' class='controlador_seta'>
			</div>";
	
	return $form;
	}

}

?>