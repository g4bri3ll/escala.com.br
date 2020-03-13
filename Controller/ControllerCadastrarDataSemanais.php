<?php

/* Este controller aqui cadastrar as data no banco de dados que nao estao cadastrada
 * na tabela data_semanais
 * os dias da semana
 * */
include_once 'Validacoes/ArrayDiasSemanais.php';
include_once 'Model/DAO/DiasSemanaisDAO.php';

class ControllerCadastrarDataSemanais{
	
	function CadastrarDiasNaData(){
		
		//Recebe um array de datas de quinze dias a frente
		$data = new ArrayDiasSemanais();
		$arrayDatas = $data->Datas();
		
		foreach ($arrayDatas as $data => $lista){

			//Tira o nome do dia da data
			$nomeIngles = date('D', strtotime($lista));
			
			//Tira o nome do dia da data para cadastrar certo para verificar a do usuario
			$dataCadastro = date('Y-m-d', strtotime($lista));
			
			//Tem que verificar se a data ja se encontrar cadastrada ou não
			$dataDAO = new DataSemanaisDAO();
			$verificaDataCadastrada = $dataDAO->RetornaData($dataCadastro);
			
			if (empty($verificaDataCadastrada)){
			
				$diSeDAO = new DiasSemanaisDAO();
				$idDiaSemana = $diSeDAO->listaId($nomeIngles);
				
				foreach ($idDiaSemana as $idDiaSemana => $listaId){
					$id = $listaId['id'];
				}
				
				$dataDAO = new DataSemanaisDAO();
				$dataDAO->cadastrar($dataCadastro, $id);
				
			}
		
		}
		
	}
	
}

?>