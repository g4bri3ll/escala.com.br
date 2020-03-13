<?php

//Atualizar com o fusio horario do brasil
date_default_timezone_set('America/Bahia');

class ArrayDiasSemanais{

	//Retorna o array com a data atual mais 15 a frente
	function Datas(){
		
		//Receber o array vazio
		$arrayData = array();
		
		//Pega a data da maquina para cadastrar
		$data = date("D Y-m-d");
		
		//Colcar na variavel uma data de 1 dias atras
		$dateAtual = date('D Y-m-d', strtotime("-1 days",strtotime($data)));
		
		for ($i = 1;$i < 17;$i++){
			
			$arrayData[$i] = date('D Y-m-d', strtotime("+".$i." days",strtotime($dateAtual)));
		
		}
		return $arrayData;
	}
	
	/*function AumentarData($data){
		
		//aumentar a data em 15 dias a frente
		$dateAtual = date('D Y-m-d', strtotime("+14 days",strtotime($data)));
		
		return $dateAtual;
		
	}*/
	
}