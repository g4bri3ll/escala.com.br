<?php

include_once 'Model/DAO/StatusUsuarioDAO.php';


//Atualizar com o fusio horario do brasil
date_default_timezone_set('America/Bahia');

class ControllerIndex{

	//Função que faz o cancelamento da data se ja passou da data de hoje
	function ControllarStatusUsuario(){
		
		$dataInicio = date('Y-m-d');
		
		//Verificar a data se for menor que a data de hoje
		$staDAO = new StatusUsuarioDAO();
		$result = $staDAO->VerificarStatusDataPassada($dataInicio);
		
		if (!empty($result)){
			
			foreach ($result as $staDAO => $valor){
				
				$id = $valor['id'];
				
				$staDAO = new StatusUsuarioDAO();
				$staDAO->alterarStatus($id);
				
				
			}
			
		}
		
	}
	
	function ControllarDiaTrabalho(){
		
		$dataInicio = date('Y-m-d');
		
		//Verificar a data se for menor que a data de hoje
		$diaDAO = new DiaTrabalhaDAO();
		$result = $diaDAO->VerificarDiaTrabalhoDataPassada($dataInicio);
		
		if (!empty($result)){
			
			foreach ($result as $staDAO => $valor){
				
				$id = $valor['id'];
				
				$diaDAO = new DiaTrabalhaDAO();
				$diaDAO->AlteraDiaTrabalho($id);
				
				
			}
			
		}
		
	}
	
}