<?php

//Atualizar com o fusio horario do brasil
date_default_timezone_set('America/Bahia');

class ValidarDiasSemanais{

	/*function retornaNomeDia(){
	 	
		$data = date('D');
	    $mes = date('M');
	    $dia = date('d');
	    $ano = date('Y');
	    
	    $semana = array(
	        'Sun' => 'Domingo', 
	        'Mon' => 'Segunda-Feira',
	        'Tue' => 'Terca-Feira',
	        'Wed' => 'Quarta-Feira',
	        'Thu' => 'Quinta-Feira',
	        'Fri' => 'Sexta-Feira',
	        'Sat' => 'Sabado'
	    );
	    
	    $mes_extenso = array(
	        'Jan' => 'Janeiro',
	        'Feb' => 'Fevereiro',
	        'Mar' => 'Marco',
	        'Apr' => 'Abril',
	        'May' => 'Maio',
	        'Jun' => 'Junho',
	        'Jul' => 'Julho',
	        'Aug' => 'Agosto',
	        'Nov' => 'Novembro',
	        'Sep' => 'Setembro',
	        'Oct' => 'Outubro',
	        'Dec' => 'Dezembro'
	    );
	    
	    return $semana[$data];
	    //Retorna o dia da semana junto com o mes e o ano
	    //echo $semana["$data"] . ", {$dia} de " . $mes_extenso["$mes"] . " de {$ano}";
	    
	}*/
	
	function retornaOsSeteDiasSemanais(){
		
		//receber o array vazio
		$arrayData = array();
		
		//Pega a data atual da maquina
		$dataAtual = date("Y-m-d");
		
		//receber data de 3 dias atras
		$novaData = date('Y-m-d', strtotime("-3 days",strtotime($dataAtual)));
		
		//Faz o for com a data e o dia da semana
		for ($i = 0;$i < 7;$i++){
			$arrayData[$i] = date('D Y-m-d', strtotime("+".$i." days",strtotime($novaData)));
		}
		
		return $arrayData;
		
	}
	
	function RetornaUltimoDiaMes(){
		
		$mes = date("m"); // M�s desejado, pode ser por ser obtido por POST, GET, etc.
		$ano = date("Y"); // Ano atual
		return $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // M�gica, plim!
		
	}
	
	function retornaSemana(){
		
		$arrayNome = array(
			'domingo', 
			'segunda-feira',
			'terca-feira', 
			'quarta-feira', 
			'quinta-feira',
			'sexta-feira', 
			'sabado'
		);
		
		$arraySigla = array(
			'Sun', 
			'Mon', 
			'Tue', 
			'Wed', 
			'Thu', 
			'Fri', 
			'Sat'
		);
		
		$semana = array(
			"nome"  => $arrayNome,
			"sigla" => $arraySigla,
		);
		
	 	return $semana;
	}
	
}

?>