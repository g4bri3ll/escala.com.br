<?php

//Atualizar com o fusio horario do brasil
date_default_timezone_set('America/Bahia');

class ValidarData{

	function FormatarData($data){

		return date('Y-m-d', strtotime($data));

	}


	//Pega a data para e soma mais uma
	function AumentarDataDoisDias($data){
		return date('Y-m-d', strtotime("+1 days",strtotime($data)));
	}

	//Colocar a data atual quinze na frente
	function AumentarData($dataParaAumenta){
		return date('Y-m-d', strtotime("+14 days",strtotime($dataParaAumenta)));
	}

	/*/Retorna para o index 7 dias para listagem
	function ArraySeteDias(){

		$data = date('Y-m-d');

		$arrayData = array();

		//Essa data aqui e para fica no centro da tela.
		$dataAtual = date('Y-m-d', strtotime("-3 days",strtotime($data)));

		for ($i = 0;$i < 7;$i++){

			$arrayData[$i] = date('Y-m-d', strtotime("+".$i." days",strtotime($dataAtual)));

		}
		return $arrayData;
	}*/

	
	/*Retorna a data para o cadastrar outro setor php
	function validarImparPar($estadoTrabalho){

		$resultado = null;
		$pareimpar = null;
		
		$dataHoje = date('Y-m-d');
		$diaHoje = date('d');

		if ( $diaHoje & 1) {
			$pareimpar = "impar";
		} else {
			$pareimpar = "par";
		}

		if ($pareimpar === $estadoTrabalho){
			//data par
			return date('Y-m-d', strtotime("-4 days",strtotime($dataHoje)));
		} else {
			//Pega a data para e soma mais uma
			//data impar
			return date('Y-m-d', strtotime("-3 days",strtotime($dataHoje)));
		}
	}
	*/
	
}
?>