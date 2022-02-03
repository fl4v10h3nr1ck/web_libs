

	function formataParaMostrar(valor, casas){
		
		if(valor==null || valor.length<=0)
			return "0"+(casas<=0?"":",".padEnd(casas+1, "0"));
		
		valor = getNumeroDeMoeda(valor)+"";

		if(valor.indexOf(".")<0)
			valor +=(casas<=0?"":".".padEnd(casas+1, "0"));
		
		var partes = valor.split(".");
				
		var aux = "";
			
		var cont = 0;
			
		for(var i=partes[0].length-1; i>=0; i--){
				
			aux = partes[0].substring(i, i+1)+aux;
				
			if(cont>=2){
				
				if(i>0)
					aux ="."+aux;
				
				cont =0;
			}
			else
				cont++;
		}
	
		if(partes.length<=1)
			return aux;
		
		if(casas>=partes[1].length)
			return aux+","+partes[1].padEnd(casas, "0");
	
		return aux+","+partes[1].substring(0, casas);
	}





	function formataNumeroEmMoeda(valor){
		
		if(valor==null || valor.length==0)
			return "0,00";
		
		var val = valor+"";

		if(val.indexOf(",")>=0)
			val = getNumeroDeMoeda(val);

		val= parseFloat(val).toLocaleString('pt-BR')+"";
		
		if(val.indexOf(",")<0)
			val +=",00";
		else{
		
			if(val.indexOf(",") == val.length -1)
				val +="00";
			else if(val.indexOf(",") == val.length -2)
				val +="0";
			else if(val.indexOf(",") < val.length -3)
				val = val.substring(0, val.indexOf(",")+3);
		}
		
		return val;
	}


	

	//revisada
	function getNumeroDeMoeda(valor){
	
		valor = ""+valor;
	
		if(valor==null || valor.length==0)
			return 0.0;
	
		valor = valor.replace(/[^\d\,\.\+\-]/g, '');
	
		if(valor.length==0)
			return 0.0;
	
		if(valor.indexOf(",")>=0)
			return valor.replace(".", "").replace(",", ".");	
		
		return valor;	
	}
	
	
	
	
	
	
	function getInteiroDeString(valor){
		
		if(valor==null)
			return 0;
		
		valor = ""+valor;
		
		valor = valor.replace(/[^\d\,\.\+\-]/g, '');
		
		if(valor.length==0)
			return 0;
		
		if(valor.indexOf(",")<0 && valor.indexOf(".")<0)
			return parseInt(valor);
		
		if(valor.indexOf(",")<0)
			return parseInt(valor.substring(0, valor.indexOf(".")));
		
		return parseInt(valor.substring(0, valor.indexOf(",")));
	}
	
	
	

	function truncaNumero(valor, casas){
		
		valor = ""+valor;
	
		valor = valor.replace(/[^\d\,\.\+\-]/g, '');
	
		if(valor.length==0)
			return 0.0;
	
		if(valor.indexOf(",")>=0)
			valor = valor.replace(".", "").replace(",", ".");	
		
		var pos_ponto = valor.indexOf(".");
		
		if(casas<0 || pos_ponto<0)
			return valor;

		if(casas==0)
			return valor.substring(0, pos_ponto);
		
		return valor.substring(0, pos_ponto+casas+1);
	}
	
	
	
	
	
	
	
	
	
	
	
