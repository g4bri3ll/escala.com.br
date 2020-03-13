<?php

include_once 'Model/Modelo/EstadoTrabalhado.php';
include_once 'Model/DAO/EstadoTrabalhadoDAO.php';
include_once 'Model/DAO/SetorDAO.php';
include_once 'Model/DAO/DataSemanaisDAO.php';

class ControllerCadDataUsuario{

	/* Esta funcao, so servi para o primeiro cadeastrado no sistema do susuario
	 * Só isso, não mexar mais.
	 * receber a data do dia selecionado pelo usuario e cadastra na base de dados...
	 * pega o id do usuario junto com todas as datas e
	 * cadastrar no usuario
	 * */
	
	function CadastrarDatasParImpar($data, $idUsuario, $idSubSetor){
		
		//pega data de 5 dias a frente
		$dataFrente = date('Y/m/d', strtotime("+4 days",strtotime($data)));
		//Pega a data de 4 dias a tras
		$dataMenor = date('Y-m-d', strtotime("-4 days",strtotime($data)));
	
		//formatar as datas para cadastrar
		$dataMenor = date_format(new DateTime($dataMenor), 'Y/m/d');
		
		//Pegar a data do cadastrado para cadastrado o id da data 
		//pega o id e o nome em ingles e as data do cadastro
		$dataSemDAO = new DataSemanaisDAO();
		$dataId = $dataSemDAO->ListaDataCadastro($dataMenor, $dataFrente);
		
		//Pegar o estadoSubSetor para ver se o dia e normal ou par_impar
		$setDAO = new SetorDAO();
		$estadoSetor = $setDAO->ListEstadoDiasSubSetor($idSubSetor);
		
		//Inicializar a variavel
		$nomeEstadoSetor = "";
		$result = false;
		
		foreach ($estadoSetor as $setDAO => $val){
			$nomeEstadoSetor = $val['estado_dias'];
		}
		
		//Faz o cadastro, caso o setor seja par_impar
		if ($nomeEstadoSetor === "par_impar"){
		
			$estadoTrabalhado = "folga";
			//Faz o cadastrado da data do usuario na base de dados ele estara sempre ativado
			for ($i = 0;$i < count($dataId);$i++){
				
				$idData = $dataId[$i]['id']; 
				
				if($estadoTrabalhado === "trabalha"){
						
					$estTra = new EstadoTrabalhado();
					$estTra->estado = "folga";
					$estTra->idUsuario = $idUsuario;
					$estTra->id_data_semanais = $idData;
					$estTra->idSubSetor = $idSubSetor;
					
					$estTraDAO = new EstadoTrabalhadoDAO();
					$result = $estTraDAO->cadastrar($estTra);
					
					$estadoTrabalhado = "folga";
					
				} else {
					
					$estTra = new EstadoTrabalhado();
					$estTra->estado = "trabalha";
					$estTra->idUsuario = $idUsuario;
					$estTra->id_data_semanais = $idData;
					$estTra->idSubSetor = $idSubSetor;
					
					$estTraDAO = new EstadoTrabalhadoDAO();
					$result = $estTraDAO->cadastrar($estTra);
					
					$estadoTrabalhado = "trabalha";
				}
				
			}
		
			
		}//Fecha o if que verificar se o setor e par_impar
		
		//Faz o cadastro caso o setor seja normal 
		else {
			
			//lista todas as data para o cadastro da data mais o nome ingles de cada dia
			for ($i = 0;$i < count($dataId);$i++){
				//Nome em ingles para verificar se o setor escolhido tem o dia para cadastrar
				//Esse nome ingles vem pela lista das datas
				$nomeIngles = $dataId[$i]['nome_ingles'];
				//Id da data para o cadastro
				$idData = $dataId[$i]['id']; 

				//Verificar no banco de dados se esse dia o setor trabalha
				$setDAO = new SetorDAO();
				$verificarNomeIngles = $setDAO->VerificarNomeInglesSubSetor($nomeIngles, $idSubSetor);
				
				$estTra = new EstadoTrabalhado();
				//Esse nome ingles vem pelo id do setor, que verificar os dias que o setor trabalha
				if(!empty($verificarNomeIngles)){ $estTra->estado = "trabalha";	} 
											else { $estTra->estado = "folga"; }
					$estTra->idUsuario = $idUsuario;
					$estTra->id_data_semanais = $idData;
					$estTra->idSubSetor = $idSubSetor;
					
					$estTraDAO = new EstadoTrabalhadoDAO();
					$result = $estTraDAO->cadastrar($estTra);
			
			}
				
		}
		return $result;
	}//fecha a funcao que cadastra as data no usuario par e impar
	
}

?>