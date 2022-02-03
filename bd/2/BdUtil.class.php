	<?php

	define("CORINGA_PREFIXO", "###");

	chdir(dirname(__FILE__)); 

	include_once getcwd().'/define.php';

	include_once 'bd.class.php';
	include_once BD_PATH_ABS_ANOTACOES.'annotations.php';
	include_once 'AnotTabela.class.php';
	include_once 'AnotCampo.class.php';



	final class BdUtil extends bd{


	private $tab_nome;
	private $tab_prefixo;
	private $tab_join;
	private $referencia;
	private $props;
	private $classe;

	private $print_querys = true;




		function __construct() {
		
			parent::__construct();
		}
		
		

		
		
		public function getPrefixoCoringa(){
			
			return CORINGA_PREFIXO;
		}
		
		

		
		

		public function novo($item){
		
		$erro = $this->prepara($item);
		if($erro<0)
		return $erro;
		
		$query = "insert into ".$this->tab_nome." (";
		$valores = "";
		
			foreach($this->props as $i=>$prop){
			
			$reflexao_prop = new ReflectionAnnotatedProperty($item, $prop->getName());
			
				if($reflexao_prop!=null){
				
				if(!$reflexao_prop->hasAnnotation('AnotCampo') || 
						$reflexao_prop->getAnnotation('AnotCampo')->ehId || 
							$reflexao_prop->getAnnotation('AnotCampo')->select_apenas)
				continue;
		
				$query .= $reflexao_prop->getAnnotation('AnotCampo')->nome.",";
				
				$valores .= $this->getValor($reflexao_prop, $prop->getValue($item)).",";	
				}
			}
		
		$query = (substr($query, strlen($query)-1, 1)==","?
						substr($query, 0, strlen($query)-1):$query);
		
		$valores = (substr($valores, strlen($valores)-1, 1)==","?
						substr($valores, 0, strlen($valores)-1):$valores);
		
		
		$query .= ") values (".$valores.")";
		
		if($this->print_querys)
			echo $query;
		
		$dados = $this->getReferencia()->query($query);
	
		if($dados===false)
			return 0;
	
		return $this->getReferencia()->lastInsertId();
		}
		
		
		
		
		
		
		public function altera($item){
		
		$erro = $this->prepara($item);
		if($erro<0)
		return $erro;
		
		$valores = "";
		$id = "";
		
			foreach($this->props as $i=>$prop){
			
			$reflexao_prop = new ReflectionAnnotatedProperty($item, $prop->getName());
		
				if($reflexao_prop!=null && $reflexao_prop->hasAnnotation("AnotCampo")){
				
					if($reflexao_prop->getAnnotation('AnotCampo')->ehId){
					
					$id = $reflexao_prop->getAnnotation('AnotCampo')->nome."=".$prop->getValue($item);
					continue;
					}
				
				
					if($reflexao_prop->getAnnotation('AnotCampo')->select_apenas)
					continue;	
				
				$valores .= $reflexao_prop->getAnnotation('AnotCampo')->nome.
							"=".
							$this->getValor($reflexao_prop, $prop->getValue($item)).",";
				
				}
			}
		
		$valores = (substr($valores, strlen($valores)-1, 1)==","?
						substr($valores, 0, strlen($valores)-1):$valores);
		
		$query = "update ".$this->tab_nome." set ".$valores." where ".$id;
		
		if($this->print_querys)
			echo $query;
		
		return $dados = $this->getReferencia()->query($query);
		}
		
		
		
		

		
		public function getComoArray($query){
		
			if( strlen($query) == 0)
				return -1;

			if( $this->referencia == null)
				$this->referencia = $this->getReferencia();
		
			if( $this->referencia ==null)
				return -2;
		
		
			$dados = $this->getReferencia()->query($query);
			
			if($dados===false)
			return null;
			
			$resultado = array();	
			
			while($row = $dados->fetch())
			$resultado[] = $row;	
			
			return $resultado;
		}
		
		
		
		
		

		
		public function getPorQuery($item, $join, $where, $orderby, $limit="", $distinto = false){
		
			$erro = $this->prepara($item);
				
			if($erro<0)
				return $erro;
		
			return $this->get($item, $join, $where, $orderby, $limit, $distinto);	
		}	
		
		
		
		
		
		

		
			
		public function getPorId($item, $id){
		
		$erro = $this->prepara($item);
		if($erro<0)
		return $erro;
		
			
		$valores = $this->get($item, null, $this->getQueryPorId($item, $id), null, false);	
		
		if(count($valores)>0)
			return $valores[0];
		
		return null;
		}	
		
		
		
		
		

		
		public function getPrimeiroOuNada($item, $join, $where, $orderby){
		
		$erro = $this->prepara($item);

		if($erro<0)
		return $erro;
		
		$regs = $this->get($item, $join, $where, $orderby, false);	
		
		if(count($regs)>0)
			return $regs[0];
		
		return null;
		}	
		
		
		
		
		
		
		
		public function deletaPorQuery($item, $where){
		
		if(strlen($where)==0)
		return -1;
		
		$erro = $this->prepara($item);
		if($erro<0)
		return $erro;

		return $this->deleta($item, $where);
		}
		


		
		
		
		public function deletaPorId($item, $id){
		
		if($id<=0)
		return -1;
		
		$erro = $this->prepara($item);
		if($erro<0)
		return $erro;


		return $this->deleta($item, $this->getQueryPorId($item, $id, false));
		}
		


		
		
		
		
		public function cont($item, $join, $where, $orderby, $distinto=false){
		
		$erro = $this->prepara($item);
		if($erro<0)
		return $erro;
		
		return count($this->get($item, $join, $where, $orderby, "", $distinto));
		}
		

		
		
		
		
		public function executaQuery($query){
		
		if( strlen($query) == 0)
		return -1;

		if( $this->referencia == null)
		$this->referencia = $this->getReferencia();
		
		if( $this->referencia ==null)
		return -2;
		
		return $this->getReferencia()->query($query);
		}



		

		
		private function prepara($item){
		
		if( $item ==null || !is_object($item))
		return -1;

		if( $this->referencia == null)
		$this->referencia = $this->getReferencia();
		
		if( $this->referencia ==null)
		return -2;
		
		
		$anot_classe = new ReflectionAnnotatedClass($item);

		
		if(!$anot_classe->hasAnnotation('AnotTabela'))
		return -3;	


		$this->tab_nome  = $anot_classe->getAnnotation('AnotTabela')->nome;
		$this->tab_prefixo  = $anot_classe->getAnnotation('AnotTabela')->prefixo;
		$this->tab_join  = $anot_classe->getAnnotation('AnotTabela')->join;

		
		$reflexao_classe = new ReflectionClass($item);
		
		$this->classe = $reflexao_classe->getName();
		
		$this->props = $reflexao_classe->getProperties(ReflectionProperty::IS_PUBLIC | 
													ReflectionProperty::IS_PROTECTED);
		
		if(count($this->props)==0)
		return -4;			
		
		return 0;
		}
		
		
		
		
		
		
		private function getValor($reflexao_prop, $valor){
		
		$valores = "";
		
		if(strlen($reflexao_prop->getAnnotation('AnotCampo')->tipo)==0 || 
			strcmp($reflexao_prop->getAnnotation('AnotCampo')->tipo, "string")==0 || 
				strcmp($reflexao_prop->getAnnotation('AnotCampo')->tipo, "char")==0)
		$valores .= $valor==null?"NULL ":"'".str_replace("'", "\'", str_replace('"', '\"', $valor))."' ";
		
			elseif(strcmp($reflexao_prop->getAnnotation('AnotCampo')->tipo, "int")==0 ||
						strcmp($reflexao_prop->getAnnotation('AnotCampo')->tipo, "boolean")==0){
			
			$valores .= strlen($valor)==0?"NULL ":(($valor===0?"'0'":$valor)." ");
			}	
			elseif(strcmp($reflexao_prop->getAnnotation('AnotCampo')->tipo, "data")==0){
			
			//include_once BD_PATH_ABS_BIB.'Data.class.php';
			
			//$valor = (new Data)->converteBRParaEUA($valor);
					
			$valores .= strlen($valor)==0?"NULL ":"'".$valor."' ";
			}
				
		return $valores;			
		}
		
		
		
		
		

		
		private function getQueryPorId($item, $valor, $com_prefixo= true){
			
		$query = "";
		
			foreach($this->props as $i=>$prop){
			
			$reflexao_prop = new ReflectionAnnotatedProperty($item, $prop->getName());
		
				if($reflexao_prop!=null){
				
					if($reflexao_prop->getAnnotation('AnotCampo')->ehId){
					
					$query = ($com_prefixo?$this->tab_prefixo.".":"").$reflexao_prop->getAnnotation('AnotCampo')->nome."=".$valor;
					break;
					}
				}
			}

		return $query;	
		}
		
		
		

		
		

		private function get($item, $join, $where, $orderby, $limit="", $distinct=false){
	
		$query = "SELECT".($distinct?" DISTINCT":"")." ";	
				
			foreach($this->props as $i=>$prop){
			
			$reflexao_prop = new ReflectionAnnotatedProperty($item, $prop->getName());
				
				if($reflexao_prop!=null && $reflexao_prop->hasAnnotation('AnotCampo')){
		
					if($reflexao_prop->getAnnotation('AnotCampo')->sem_prefixo !== true){
			
						if(strlen($reflexao_prop->getAnnotation('AnotCampo')->prefixo)>0)
						$query .= $reflexao_prop->getAnnotation('AnotCampo')->prefixo;
						else
						$query .= $this->tab_prefixo;

					$query .= ".";				
					}
				
				$query .= $reflexao_prop->getAnnotation('AnotCampo')->nome.
					(strlen($reflexao_prop->getAnnotation('AnotCampo')->apelido)>0?" as ".$reflexao_prop->getAnnotation('AnotCampo')->apelido:"").",";
				}
			}
			
		$query = (substr($query, strlen($query)-1, 1)==","?
						substr($query, 0, strlen($query)-1):$query)." ";
		
		
		$query .= " FROM ".$this->tab_nome." as ".$this->tab_prefixo." ".
					str_replace(CORINGA_PREFIXO, $this->tab_prefixo, $this->tab_join).
					(strlen($join)>0?" ".str_replace(CORINGA_PREFIXO, $this->tab_prefixo, $join):"").
					(strlen($where)>0?" WHERE ".str_replace(CORINGA_PREFIXO, $this->tab_prefixo, $where):"").
					(strlen($orderby)>0?" ORDER BY ".str_replace(CORINGA_PREFIXO, $this->tab_prefixo, $orderby):"").
					(strlen($limit)>0?" limit ".$limit:"");
		
		if($this->print_querys)
			echo $query;
		
					
		//$reg = mysql_query( $query, $this->referencia);
		
		$reg = $this->getReferencia()->query($query);
		
		if($reg===false)
		return null;
		
			if($reg){
				
				
				//if( mysql_num_rows($reg) > 0){
		
				$registros = array();	
		
					//while($aux = mysql_fetch_assoc($reg)){
					
					while($aux = $reg->fetch()){
					
					$item = new $this->classe();
					
						foreach($this->props as $prop){
					
						$reflexao_prop = new ReflectionAnnotatedProperty($item, $prop->getName());
				
							if($reflexao_prop!=null && $reflexao_prop->hasAnnotation('AnotCampo')){
							
							if(strlen($reflexao_prop->getAnnotation('AnotCampo')->tipo)>0 && 
								strcmp($reflexao_prop->getAnnotation('AnotCampo')->tipo, "data")==0){
							
								//include_once BD_PATH_ABS_BIB.'Data.class.php';
							
								$prop->setValue($item, $aux[$reflexao_prop->getAnnotation('AnotCampo')->nome]);	
							}							
							else
								$prop->setValue($item, 
												str_replace("\'", "&#039;", str_replace('\"', "&quot;", 
														(strlen($reflexao_prop->getAnnotation('AnotCampo')->apelido)>0?
															$aux[$reflexao_prop->getAnnotation('AnotCampo')->apelido]:
															$aux[$reflexao_prop->getAnnotation('AnotCampo')->nome]))));
							}
							
						}	
					
					
					array_push($registros, $item);
					}
				
				return $registros;	
				//}
			}
		
		
		return null;	
		}
		
		
		
		

		
		
		private function deleta($item, $where){
		
		$query=  "delete from ".$this->tab_nome." where ".$where;
		
		if($this->print_querys)
			echo $query;
		
		return $this->getReferencia()->query($query);	
		}
		
		
		
		
		
/*

		private function dataFormatoBRParaUs($data){
		
		$data = "".$data;
				
		if(strlen($data)!=10)
		return "";
		
		if(strpos($data, "-")!==false)
		return $data;


		return substr($data, 6)."-".substr($data, 3, 2)."-".substr($data, 0, 2);
		}

*/

		
		
		
		public function getCampoDeProp($item, $nome_prop){
				
		$reflexao_prop = new ReflectionAnnotatedProperty($item, $nome_prop);
		
		if($reflexao_prop!=null && $reflexao_prop->getAnnotation('AnotCampo')!=null)
		return $reflexao_prop->getAnnotation('AnotCampo')->nome;
		
		return null;
		}
		
		
		
		
		
		
		public function getTipoDeProp($item, $nome_prop){
				
		$reflexao_prop = new ReflectionAnnotatedProperty($item, $nome_prop);
		
			if($reflexao_prop!=null && $reflexao_prop->getAnnotation('AnotCampo')!=null){
			
			$tipo = $reflexao_prop->getAnnotation('AnotCampo')->tipo;
			
			return strlen($tipo)==0?"string":$tipo;
			}
			
		return "string";
		}
		
		
		
		
		
		
		public function getPrefixoDeProp($item, $nome_prop){
		
		$this->prepara($item);
		
		$reflexao_prop = new ReflectionAnnotatedProperty($item, $nome_prop);
		
			if($reflexao_prop!=null && 
				$reflexao_prop->getAnnotation('AnotCampo')!=null){
			
			
			if($reflexao_prop->getAnnotation('AnotCampo')->sem_prefixo)
				return "";
		
			$prefixo = $reflexao_prop->getAnnotation('AnotCampo')->prefixo;
			
			return (strlen($prefixo)==0?$this->tab_prefixo:$prefixo);
			}
			
		return $this->tab_prefixo;
		}
		
		
		
		
		
		public function getValorDeCampoId($item){
		
		$reflexao_classe = new ReflectionClass($item);
		
		$props = $reflexao_classe->getProperties(ReflectionProperty::IS_PUBLIC | 
													ReflectionProperty::IS_PROTECTED);
		
			if(count($props)>0){
			
				foreach($props as $prop){
			
				$reflexao_prop = new ReflectionAnnotatedProperty($item, $prop->getName());
				
					if($reflexao_prop!=null){
					
					if($reflexao_prop->getAnnotation('AnotCampo')->ehId)
					return $prop->getValue($item);
					
					}
				}
			}
				
		return null;
		}
		

	}
	?>