<?php
include_once 'Conexao/Conexao.php';

class TrocaServicoDAO {

	private $conn = null;
	
	public function cadastra(TrocaServico $troca) {
		
		try {
			
			$sql = "INSERT INTO troca_servico (id_usuario_solicitante, id_usuario_tira,  
					data_solicitante, data_tira, motivo_troca) 
					VALUES ('" . $troca->idUsuarioSolicitante . "',	'" . $troca->idUsuarioTira . "', 
					'" . $troca->dataSolicitante . "', '" . $troca->dataTira . "', '" . $troca->motivoTroca . "')";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true;
			
		} catch ( PDOException $e ) {
			return "Erro de banco de dados";
		}
			
	}
	
	/* Verificar se ja existe uma troca para essa data, se o usuario qe for tirar tiver ja um troca o sitema nÃ£o liberar
	 * e verificar tambem se ele possui alguma suspensão no status usuario e retorna o valor
	 * Valida tambem se o usuario esta de folga neste. Poque se o usuario estiver trabalhando não tem como fazer a troca do servico
	 * */
	public function VerificarCad($idUsuSession, $dataEleTira, $idUsuTira, $dataVoceTira){
		
		$sql = "SELECT u.apelido, u.id, su.data_inicio, su.data_final, ts.data_tira, ts.data_solicitante, su.id_usuario, ds.data,
				CASE 
				WHEN ts.id_usuario_tira = '".$idUsuTira."' 
				AND ts.id_usuario_solicitante = '".$idUsuSession."' 
				AND cts.libera_troca = '' 
				AND ts.data_tira = '".$dataEleTira."' 
				AND ts.data_solicitante = '".$dataVoceTira."' 
				THEN 'ja_possui_troca_servico' 
				WHEN (su.id_usuario = '".$idUsuTira."' OR su.id_usuario = '".$idUsuSession."') 
				AND su.status = 'valido' 
				AND ('".$dataEleTira."' BETWEEN su.data_inicio AND su.data_final 
				OR '".$dataVoceTira."' BETWEEN su.data_inicio AND su.data_final) 
				THEN 'possui_folga' 
				WHEN (et.id_usuario = '".$idUsuSession."' OR et.id_usuario = '".$idUsuTira."') 
				AND ('".$dataVoceTira."' = ds.data OR '".$dataEleTira."' = ds.data)
				AND et.estado = 'trabalha'
				THEN 'trabalhando'
				ELSE 'nao_possui_pendencias' 
				END as valida_usuario 
				FROM usuario u 
				RIGHT JOIN ativacao_usuario au ON(u.id = au.id_usuario) 
				RIGHT JOIN status_usuario su ON(u.id = su.id_usuario) 
				RIGHT JOIN troca_servico ts ON(u.id = ts.id_usuario_tira OR u.id = ts.id_usuario_solicitante) 
				INNER JOIN comfirmar_troca_servico cts ON(ts.id = cts.id_troca_servico) 
				RIGHT JOIN estado_trabalhado et ON(u.id = et.id_usuario)
				LEFT JOIN data_semanais ds ON(et.id_data_semanais = ds.id)
				WHERE ('".$dataVoceTira."' = ds.data OR '".$dataEleTira."' = ds.data)
				AND et.estado = 'trabalha'
				OR ((ts.data_tira = '".$dataEleTira."' AND ts.data_solicitante = '".$dataVoceTira."') 
				OR (('".$dataVoceTira."' BETWEEN su.data_inicio AND su.data_final) OR ('".$dataEleTira."' BETWEEN su.data_inicio AND su.data_final)) 
				AND u.id IN ('".$idUsuTira."', '".$idUsuSession."') 
				AND su.id_usuario IN ('".$idUsuTira."', '".$idUsuSession."')) 
				AND au.estado_ativado = 'ativado'
				GROUP BY u.apelido";
		
		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$resultado = mysqli_query($conn->getCon(), $sql);
		
		$conn->closeConnect ();
		
		$array = array();
		while ($row = mysqli_fetch_assoc($resultado)) {
			$array[]=$row;
		}
		return $array;
		
	}
	
	//Lista todas as troca de servicos
	public function BuscarPeloNome($nome){
		
		$sql = "SELECT ts.*, cts.* FROM usuario u 
				INNER JOIN troca_servico ts ON (u.id = ts.id_usuario_solicitante) 
				INNER JOIN comfirmar_troca_servico cts ON (ts.id = cts.id_troca_servico) 
				WHERE u.nome_usuario LIKE '".$nome."'";

		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$resultado = mysqli_query($conn->getCon(), $sql);
		
		$conn->closeConnect ();
		
		$array = array();
		while ($row = mysqli_fetch_assoc($resultado)) {
			$array[]=$row;
		}
		return $array;
		
	}
	
	//Lista todas as troca de servicos
	public function ListaTudo(){
		
		$sql = "SELECT * FROM troca_servico ts 
				INNER JOIN comfirmar_troca_servico cts ON (ts.id = cts.id_troca_servico)";

		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$resultado = mysqli_query($conn->getCon(), $sql);
		
		$conn->closeConnect ();
		
		$array = array();
		while ($row = mysqli_fetch_assoc($resultado)) {
			$array[]=$row;
		}
		return $array;
		
	}
	
	//Lista troca de serviço feito pelo usuario 
	public function ListaTrocaServico($datInicio, $unidade, $setor){
		
		$sql = "SELECT * FROM troca_servico ts 
				INNER JOIN comfirmar_troca_servico cts ON (ts.id = cts.id_troca_servico) 
				INNER JOIN usuario u ON (u.id = ts.id_usuario_solicitante)
				INNER JOIN setor s ON (u.id_setor = s.id)
				INNER JOIN unidade_trabalho ut ON (u.id_unidade_trabalho = ut.id)
				WHERE ts.data_solicitante = '".$datInicio."' 
				AND ts.data_tira = '".$datInicio."' 
				AND cts.pendente LIKE 'nao' 
				AND cts.status LIKE 'verificado'
				AND s.id = '".$setor."'
				AND ut.id = '".$unidade."'";

		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$resultado = mysqli_query($conn->getCon(), $sql);
		
		$conn->closeConnect ();
		
		$array = array();
		while ($row = mysqli_fetch_assoc($resultado)) {
			$array[]=$row;
		}
		return $array;
		
	}
	
	//Retorna o ultimo id do cadastrado para cadastrar na tela de comfirmacao de troca de servico
	public function RetornaUltimoId(){
		
		$sql = "SELECT MAX(id) as id FROM troca_servico";

		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$resultado = mysqli_query($conn->getCon(), $sql);
		
		$conn->closeConnect ();
		
		$array = array();
		while ($row = mysqli_fetch_assoc($resultado)) {
			$array[]=$row;
		}
		return $array;
		
	}
	
}

?>