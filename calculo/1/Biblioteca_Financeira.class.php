<?php



include_once 'Biblioteca.class.php';




class Biblioteca_Financeira extends Biblioteca{
	
	
	
	
	
	public function extraiDouble($valor){
		
	if( strlen($valor) == 0)
	return (double)0;
	
	
	if(is_float($valor))
	return $valor;


	$valor = preg_replace("/[^(0-9 . , - )]/", "", $valor);
	return (double)str_replace(",",".", str_replace(".","", $valor));
	}
	
	
	
	
	
	
	public function  formataMoeda($valor) {
	
	$valor = explode( '.', $this->extraiDouble( $valor).'.');
	
	$negativo = false;
		if($valor[0][0] == '-' ){
		$valor[0] = substr($valor[0], 1 );
		$negativo = true;
		}
		
		switch(strlen($valor[1])){
		
		case 0:
		$valor[1] = '0';
		case 1:
			$valor[1] .='0';
				case 2:
				break;
					default:
					$valor[1] = substr($valor[1], 0 , 2);
		}
	
	$valor_format = '';
	$resto = strlen($valor[0])%3;
	$primeira_parte = substr($valor[0], 0, $resto);
	$segunda_parte = substr($valor[0], $resto, strlen($valor[0]));
	
	for($i = 0; $i<strlen($segunda_parte); $i+=3)
	$valor_format.=substr($segunda_parte, $i, 3).($i == (strlen($segunda_parte)-3)?"":".");
	
	$final = $primeira_parte;
	if( strlen($segunda_parte)>0 && strlen($primeira_parte)>0)
	$final .="."; 
	
	
	return ($negativo?'-':'').$final.$valor_format.','.$valor[1];
	}
	
	
	
	

	
	public function soma($reg){
	
	$cont = "0.00";
		foreach($reg  as $valor){
		
		$aux = $this->extraiDouble($valor);
		$cont += $aux;
		}
	
	return $cont;
	}
	
	
	
	
	//retorna um double
	public function subtrai($minuendo , $subtraendo){
	
	return $this->extraiDouble($minuendo) - $this->extraiDouble($subtraendo);
	}
	
	
	
	
	
	
	
	//retorna um double
	public function multiplica($multiplicando , $multiplicador=0){
	
	
	return $this->extraiDouble($multiplicando) * $this->extraiDouble($multiplicador);
	}
	
	
	
	
	
	
	
	//retorna um double
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
	
	
		
	
}

?>


