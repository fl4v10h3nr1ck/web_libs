<?php

/*

converteBRParaEUA
converteEUAParaBR

validaDataBR
validaDataEUA

comparaDatasBR
comparaDatasEUA

diferencaDatasEmMeses
diferencaDatasEmDias
diferencaDatasEmMin

getNomeDeMes

somaDiasADataEUA
*/
 
 
 

class Data{


	
	
	
	public function converteBRParaEUA($data, $separador="-"){
	
		$data = preg_replace("/[^0-9\s]/", "", $data);
	
		if(strlen($data)!=8)
			return null;
	
		return substr($data, 4, 4).$separador.substr($data, 2, 2).$separador.substr($data, 0, 2);
	}
		
	


	public function converteEUAParaBR($data, $separador="/"){
	
		$data = preg_replace("/[^0-9\s]/", "", $data);
	
		if(strlen($data)!=8)
			return null;
	
		return substr($data, 6, 2).$separador.substr($data, 4, 2).$separador.substr($data, 0, 4);
	}
	
		
		
		
	public function converteEUAParaBRComHorario($data, $hora, $min, $separador="/"){
	
		$data = preg_replace("/[^0-9\s]/", "", $data);
	
		if(strlen($data)!=8)
			return null;
	
		return substr($data, 6, 2).$separador.substr($data, 4, 2).$separador.substr($data, 0, 4)
					." ".str_pad($hora, 2, "0", STR_PAD_RIGHT).":".str_pad($min, 2, "0", STR_PAD_RIGHT);
	}	
		
		
		
	
	
	public function validaDataBR($data){
	
		$data = preg_replace("/[^0-9]/", "", $data);
	
		if(strlen($data) != 8)
			return false;
		
		if(substr($data, 0, 2) <=0 || substr($data, 0, 2) > 31 || substr($data, 2, 2) <=0 || substr($data, 2, 2) > 12)
			return false;
	
		return true;
	}
	
	
	
		
		
	public function validaDataEUA( $data){
	
		$soment_num = preg_replace("/[^0-9]/", "", $data);
	
		if(strlen($soment_num) != 8)
			return false;
		
		if( substr($soment_num, 6, 2) > 31 || substr($soment_num, 4, 2) > 12)
			return false;
	
		$dia = substr($data, 8, 2);
		$mes = substr($data, 5, 2);
		$ano = substr($data, 0, 4);

		return checkdate($mes,$dia,$ano);
	}
	
	
	
		
		
	public function comparaDatasBR($inicio, $fim){
	 
		$inicio = 	$this->converteBRParaEUA( $inicio, "-");
		$fim = 		$this->converteBRParaEUA( $fim, "-");

		return $this->comparaDatasEUA($inicio, $fim);
	}
	
	

	
		
	public function comparaDatasEUA($inicio, $fim){
	 
		if($inicio==null || 
				$fim==null || 
					strtotime($inicio)==null || 
						strtotime($fim)==null)
			return null;
		
		
		if(strtotime($inicio) < strtotime($fim))
			return -1;
		
		if(strtotime($inicio) == strtotime($fim))
			return 0;
		
		return 1;
	}
	
	
	
	
	public function comparaDatasEUAComMin($data_inicio, $hora_inicio, $min_inicio, $data_fim, $hora_fim, $min_fim){
	 
		if($data_inicio==null || 
				$data_fim==null || 
					strtotime($data_inicio)==null || 
						strtotime($data_fim)==null)
			return null;
		
		$aux_inicio = strtotime($data_inicio." ".$hora_inicio.":".$min_inicio);
		
		$aux_fim = strtotime($data_fim." ".$hora_fim.":".$min_fim);
		
		
		if($aux_inicio < $aux_fim)
			return -1;
		
		if($aux_inicio == $aux_fim)
			return 0;
		
		return 1;
	}
	
	
	

	
	public function diferencaDatasEmMeses($inicio, $fim, $br=true){
		
		if($br){
			
			$inicio = 	$this->converteBRParaEUA( $inicio, "-");
			$fim = 		$this->converteBRParaEUA( $fim, "-");
		}
	

		$date = new DateTime($inicio); 
		$aux = $date->diff(new DateTime($fim));
		
		return ($aux->format('%Y')*12)+$aux->format('%m');
	}
	
	
	
	
	
	public function diferencaDatasEmDias($inicio, $fim, $br=true){
		
		if($br){
			
			$inicio = 	$this->converteBRParaEUA( $inicio, "-");
			$fim = 		$this->converteBRParaEUA( $fim, "-");
		}

		$diferenca = strtotime($fim) - strtotime($inicio);

		return floor($diferenca / (60 * 60 * 24));
	}
	
	
	
	
	
	
	public function diferencaDatasEmMin($data_inicio, $hora_inicio, $min_inicio, $data_fim, $hora_fim, $min_fim, $br=true){
		
		if($br){
			
			$data_inicio = 	$this->converteBRParaEUA( $data_inicio, "-");
			$data_fim = 		$this->converteBRParaEUA( $data_fim, "-");
		}

		return ((strtotime($data_fim) - strtotime($data_inicio))/60)+(($hora_fim-$hora_inicio)*60) +  ($min_fim-$min_inicio);
	}
	
	
	
	
	
	public function getNomeDeMes($mes){
		
		foreach(array("Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro", "Outubro", "Novembro", "Dezembro") as $i=>$valor){
			
			if(($mes-1)==$i)
				return $valor;
		}

		return $mes;
	}
	
	
	
	
	
	public function somaDiasADataEUA($data, $quant_dias){
		
		return date('Y-m-d', strtotime("+".$quant_dias." days", strtotime($data)));
	}
	
	
	
	
	
	public function subtraiDiasDeDataEUA($data, $quant_dias){
		
		return date('Y-m-d', strtotime("-".$quant_dias." days", strtotime($data)));
	}
	
	
}

?>
