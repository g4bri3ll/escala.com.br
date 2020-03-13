<?php

include_once 'Model/DAO/DataSemanaisDAO.php';
include_once 'Model/DAO/EstadoTrabalhadoDAO.php';
include_once 'Model/Modelo/EstadoTrabalhado.php';

class ControllerCadastrarDataUsuario{
	
	function CadastrarDiasNosUsuarios(){
		
		//Buscar a data da maquina de hoje
		$dataHoje = date('Y-m-d');
		//pega data a frente
		$dataFrente = date('Y-m-d', strtotime("+4 days",strtotime($dataHoje)));
		
		//Buscar o ultimo dia cadastrado a data no usuario para cadastrar mais datas
		//cadastrar nele e verificar se ele esta ativo
		$etDAO = new EstadoTrabalhadoDAO();
		$arrayIds = $etDAO->RetornUltimosIds();
		
		//Nesse for receber um usuario por vez para efetuar o cadastrdo
		for ($i = 0; $i < count($arrayIds);$i++){

			//Pegar o id do usuario
			$idUsuario = $arrayIds[$i]['id_usuario'];
			//Pegar o id so sub setor para cadastrar
			$idSubSetor = $arrayIds[$i]['id_sub_setor'];
			
			//Verificar se o sub setor e par_impar ou normal para cadastrar as data neste setor
			$estadoSubSetor = $arrayIds[$i]['estado_dias'];
			
			if ($estadoSubSetor === "par_impar"){
				
				//Pega a ultima data trabalhada do banco de dados
				$UltimaData = $arrayIds[$i]['data'];
				
				/*Verificar se a ultima data cadastrada no banco de dados
				 *e menor que a data que aumentamos dai de cima em 8 dias a frente 
				*/   
				if ($UltimaData < $dataFrente){
					
					//Retorna os id das data que falta para cadastrar
					$datDAO = new DataSemanaisDAO();
					$arrayDataCad = $datDAO->ListaDatasParaCadastrada($UltimaData, $dataFrente);
					
					//Pega o ultimo estado de trabalho do usuario
					$estadoTrabalhado = $arrayIds[$i]['estado'];
					
					//Faz o cadastrado da data do usuario na base de dados ele estara sempre ativado
					for ($a = 0;$a < count($arrayDataCad);$a++){
						
						$idData = $arrayDataCad[$a]['id']; 
						
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
			
					
				}
				
			} else {
				
				//Pega a ultima data trabalhada do banco de dados
				$UltimaData = $arrayIds[$i]['data'];
				
				/*Verificar se a ultima data cadastrada no banco de dados
				 *e menor que a data que aumentamos dai de cima em 8 dias a frente 
				*/  
				if ($UltimaData < $dataFrente){
					
					//Retorna os id das data que falta para cadastrar
					$datDAO = new DataSemanaisDAO();
					$arrayDataCad = $datDAO->ListaDatasParaCadastrada($UltimaData, $dataFrente);
					
					//lista todas as data para o cadastro da data mais o nome ingles de cada dia
					for ($b = 0;$b < count($arrayDataCad);$b++){
						//Nome em ingles para verificar se o setor escolhido tem o dia para cadastrar
						//Esse nome ingles vem pela lista das datas
						$nomeIngles = $arrayDataCad[$b]['nome_ingles'];
						//Id da data para o cadastro
						$idData = $arrayDataCad[$b]['id']; 
		
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
				
			}
			
		}//Fechar o for, que receber o id dos usuario por usuario
		
	}
	
}

?>