<?php

//Atualizar com o fusio horario do brasil
date_default_timezone_set('America/Bahia');

class ValidarHoras{

	function retornaHora($horas){

	  
		//Considerando tudo que você já fez...
		$pattern = '/[0-9]{2}:[0-9]{2}/';

		if(!preg_match($pattern, $horas)){
			return false;
		} else {
			//Divide a string
			$tempo = explode(':', $horas);
			//Verifica os intervalos
			if($tempo[0] < 0 || $tempo[0] >= 24){
			return false;
			} else {
				return true;
			}

		}

	}

}

?>