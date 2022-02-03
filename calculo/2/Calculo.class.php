<?php


/*versao 2.0 (antiga Biblioteca_Financeira)*/

/*


validaMoeda($valor, $pode_zero =false)->bool
FormataMoeda($valor)->string
temSomenteNumeros( $valor)->bool
soma($parcela1, $parcela2)->string
extraiDouble($valor)->double
subtrai($minuendo , $subtraendo)->string
multiplica($multiplicando , $multiplicador=0)->string
dividi($dividendo , $divisor)->string
stringZero($val)->bool

*/




class Calculo{
	
	
	
	
	
	public function formataParaCalcular($valor, $natural=false){
		
		if(strlen($valor)==0)
			return "0";
		
		$posicao_virg = strripos($valor, ',');
	
		if($posicao_virg === false){
			
			if($natural)
				return preg_replace("/[^(^\d-)]/", "", $valor);
			
			$posicao_ponto = strripos($valor, '.');
			
			if($posicao_ponto === false)
				return preg_replace("/[^(^\d-)]/", "", $valor);
			
				
			$p_natural = substr($valor, 0, $posicao_ponto);
			$p_decimal = substr($valor, $posicao_ponto+1);
			
			return preg_replace("/[^(^\d-)]/", "", $p_natural).'.'.preg_replace("/[^0-9]/", "", $p_decimal);	
		}
		
		if($natural)
			return preg_replace("/[^(^\d-)]/", "", substr($valor, 0, $posicao_virg));
		
			
		$p_natural = substr($valor, 0, $posicao_virg);
		$p_decimal = substr($valor, $posicao_virg+1);
			
		return preg_replace("/[^(^\d-)]/", "", $p_natural).'.'.preg_replace("/[^0-9]/", "", $p_decimal);
	}
	
	
	
	
	
	public function formataParaMostrar($valor, $moeda = true){
		
		if(strlen($valor)==0 || $valor==0 || ($valor>0 && $valor< 0.01))
			return $moeda?"0,00":"0";
		
		$posicao_virg = strripos($valor, ',');
	
		$natural = "";
		$decimal = "";
	
		//nao tem virgula
		if($posicao_virg === false){
			
			$posicao_ponto = strripos($valor, '.');
			
			//nao tem ponto nem virgula
			if($posicao_ponto === false){
				
				$natural = preg_replace("/[^(^\d-)]/", "", $valor);
				$decimal = $moeda?"00":"";
			}
			else{
				
				$natural = preg_replace("/[^(^\d-)]/", "", substr($valor, 0, $posicao_ponto));
				$decimal =  preg_replace("/[^0-9]/", "", substr($valor, $posicao_ponto+1));
			}
				
		}
		else{
			
			$natural = preg_replace("/[^(^\d-)]/", "", substr($valor, 0, $posicao_virg));
			$decimal = preg_replace("/[^0-9]/", "", substr($valor, $posicao_virg+1));
		}
		
		if($moeda)
			$decimal = substr(str_pad($decimal, 2, "0",STR_PAD_RIGHT), 0, 2);
		else{
			
			if($decimal==0)
				$decimal = "";
		}
		
		$negativo = "";
		if(strcmp(substr($natural, 0, 1), "-")==0){
			
			$negativo = "-";
			$natural = substr($natural, 1);
		}
		

		$resto = strlen($natural)%3;
		$primeira_parte = substr($natural, 0, $resto);
		$segunda_parte = substr($natural, $resto, strlen($natural));
		
		$valor_format = "";
	
		for($i = 0; $i<strlen($segunda_parte); $i+=3)
			$valor_format.=substr($segunda_parte, $i, 3).($i == (strlen($segunda_parte)-3)?"":".");
	
		$final = $primeira_parte;
		if( strlen($segunda_parte)>0 && strlen($primeira_parte)>0)
			$final .="."; 
	
		return $negativo.$final.$valor_format.(strlen($decimal)>0?",":"").$decimal;
	}
	
	
	
	
	
	public function formataParaMostrarDecimal($valor){
	
		if(strlen($valor)==0)
			return "0,00";
			
		$posicao_virg = strripos($valor, ',');
		
		$natural = "";
		$decimal = "";
	
		//nao tem virgula
		if($posicao_virg === false){
			
			$posicao_ponto = strripos($valor, '.');
			
			//nao tem ponto nem virgula
			if($posicao_ponto === false){
				
				$natural = preg_replace("/[^(^\d-)]/", "", $valor);
				$decimal = "";
			}
			else{
				
				$natural = preg_replace("/[^(^\d-)]/", "", substr($valor, 0, $posicao_ponto));
				$decimal =  preg_replace("/[^0-9]/", "", substr($valor, $posicao_ponto+1));
			}		
		}
		else{
			
			$natural = preg_replace("/[^(^\d-)]/", "", substr($valor, 0, $posicao_virg));
			$decimal = preg_replace("/[^0-9]/", "", substr($valor, $posicao_virg+1));
		}
		
		if($decimal==0)
			$decimal = "";
		
		$resto = strlen($natural)%3;
		$primeira_parte = substr($natural, 0, $resto);
		$segunda_parte = substr($natural, $resto, strlen($natural));
		
		$valor_format = "";
	
		for($i = 0; $i<strlen($segunda_parte); $i+=3)
			$valor_format.=substr($segunda_parte, $i, 3).($i == (strlen($segunda_parte)-3)?"":".");
	
		$final = $primeira_parte;
		if( strlen($segunda_parte)>0 && strlen($primeira_parte)>0)
			$final .="."; 
	
		return $final.$valor_format.(strlen($decimal)>0?",":"").$decimal;
	}
	
	
	
	
	
		
	public function temSomenteNumeros( $valor){
		
		if( strlen($valor)==0 || 
			strlen(preg_replace("/[^0-9]/", "", $valor)) != strlen($valor))
			return false;
			
	return true;
	}
	

	
	
	
	public function soma($parcela1, $parcela2){
	
	return $this->extraiDouble($parcela1) + $this->extraiDouble($parcela2);
	}
	
	
	
	
	public function extraiDouble($valor){
		
	if( strlen($valor) == 0)
	return (double)0;
	
	
	if(is_float($valor))
	return $valor;

	$valor = preg_replace("/[^(0-9 . , - )]/", "", $valor);
	
		if(strpos($valor, ",") !== false)
			return (double)str_replace(",",".", str_replace(".","", $valor));
		else
			return (double)$valor;
	}
	

	
	
	
	public function subtrai($minuendo , $subtraendo){
	
	return $this->extraiDouble($minuendo) - $this->extraiDouble($subtraendo);
	}
	
	
	
	
	
	public function multiplica($multiplicando , $multiplicador=0){
	
	
	return $this->extraiDouble($multiplicando) * $this->extraiDouble($multiplicador);
	}
	
	
	

	public function dividi($dividendo , $divisor){
	
	if( $divisor == 0)
	return false;
	
	return $this->extraiDouble($dividendo) / $this->extraiDouble($divisor);
	}
	
	
	
	

	public function stringZero($val){
		
	if($this->extraiDouble($val) == 0)	
	return true;	
	
	return false;
	}
	
	
	
	
	
	public function truncaNumero($valor, $casas){
		
		$valor = "".$valor;
	
		if(strlen($valor)==0)
			return "0.0";
	
		$pos_ponto = strrpos($valor, ",");
	
		if($pos_ponto>0 || $pos_ponto===0)
			$valor = str_replace (",", ".", str_replace(".", "", $valor));	
		
		$pos_ponto = strrpos($valor, ".");
		
		if($casas<0 || $pos_ponto===false)
			return $valor;

		if($casas==0)
			return substr($valor, 0, $pos_ponto);
		
		return substr($valor, 0, $pos_ponto+$casas+1);
	}
	
	
	
		
/*		
	public function  validaMoeda($valor, $pode_zero =false) {
	
		$aux = str_replace('.', '', $valor);
		$aux = str_replace(',', '', $aux);
		
		if(!$this->somenteNUM($aux, $pode_zero, 1, 15))
			return false;
	
		if(substr_count($valor, ',') > 1 )
			return false;
	
		if( strripos($valor, ',') ===false ||
				strripos($valor, ',') == (strlen($valor) - 3) || 
					strripos($valor, ',') == (strlen($valor) - 2))
			return true;	
		
	return false;
	}
	
	
	


	
	public function  FormataMoeda($valor) {
	
		if(strlen($valor)==0)
			return "0,00";
	
		$valor_format = "";
		$aux = str_replace('.', ',', "".$valor);

		$decimal = "";
		$posicao_virg = strripos($aux, ',');
	
		if($posicao_virg === false)
			$decimal = ",00";
		else{	
			$decimal = substr($aux, $posicao_virg, 2);
		
			if(strlen($decimal )== 2)
				$decimal .="0";
		
			if(substr($aux, 0, 1 ) =='-')
				$aux = "-".preg_replace("/[^0-9]/", "", substr($aux, 1, $posicao_virg));
			else
				$aux = preg_replace("/[^0-9]/", "", substr($aux, 0, $posicao_virg));
		}
	
	
		$resto = strlen($aux)%3;
		$primeira_parte = substr($aux, 0, $resto);
		$segunda_parte = substr($aux, $resto, strlen($aux));
	
		for($i = 0; $i<strlen($segunda_parte); $i+=3)
			$valor_format.=substr($segunda_parte, $i, 3).($i == (strlen($segunda_parte)-3)?"":".");
	
		$final = $primeira_parte;
		if( strlen($segunda_parte)>0 && strlen($primeira_parte)>0)
			$final .="."; 
	
		return $final.$valor_format.$decimal;
	}
	}
	
	
	*/
		
}

?>


