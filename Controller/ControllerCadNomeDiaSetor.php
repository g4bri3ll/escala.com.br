<?php

include_once 'Model/DAO/SetorDAO.php';

class ControllerCadNomeDiaSetor{
	
	function CadNomeDiaSetor($diasArray, $nome, $idSetor){
		
		$result = false;
		
		$setDAO = new SetorDAO();
		$ultimoCadSetor = $setDAO->ListaUltimoCad($nome, $idSetor);
		
		foreach ($ultimoCadSetor as $setDAO => $a){
			$idSubSetor = $a['id'];
		}
		
		for ($i = 0;$i < count($diasArray);$i++){
			
			$status = "ativado";
			
			$setDAO = new SetorDAO();
			$res = $setDAO->cadastraNomeDias($idSubSetor, $diasArray[$i], $status);
			$result = $res;
		}
		return $result;
	}
	
}

?>
