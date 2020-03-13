<?php
include_once 'Conexao/Conexao.php';

class StatusUsuarioDAO {

	private $conn = null;
	
	public function cadastra(StatusUsuario $statusUsu) {
		
		try {
			
			$sql = "INSERT INTO status_usuario (status, data_inicio, data_final, id_usuario, id_tipos_folgas) 
					VALUES ('" . $statusUsu->status . "', '" . $statusUsu->dataInicio . "', '" . $statusUsu->dataFinal . "',
					'" . $statusUsu->idUsuario . "', '" . $statusUsu->idTipoFolga . "')";
			
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
	
	//alterar o tipo de status do usuario se a data ja passou para o ControllerIndex.php
	public function alterarStatus($id) {
		
		try {
			
			$sql = "UPDATE status_usuario SET status='invalido' WHERE id = '" . $id . "'";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true;
			
		} catch (PDOException $e) {
			
			return "erro de banco de dados"; 
			
		}
		
	}
	
	/*
	public function cadastraAtestado(StatusUsuario $statusUsu) {
		
		try {
				
			$sql = "INSERT INTO status_usuario (status, data_atestado, id_usuario) 
			VALUES ('" . $statusUsu->status . "', '" . $statusUsu->dataAtestado . "', '" . $statusUsu->idUsuario . "')";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
							
			return true;
			
		} catch ( PDOException $e ) {
			return "Erro de banco de dados";		
		}
			
	}*/
	/*/
	public function cadastraDispensa(StatusUsuario $statusUsu) {
	
		try {
			
			$sql = "INSERT INTO status_usuario (status, data_dispensa, id_usuario) 
			VALUES ('" . $statusUsu->status . "', '" . $statusUsu->dataDispensa . "', '" . $statusUsu->idUsuario . "')";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
							
			return true;
			
		} catch ( PDOException $e ) {
			return "Erro de banco de dados";
		}
		
	}*/
/*/
	public function cadastraAfastamento(StatusUsuario $statusUsu) {
	
		try {
			
			$sql = "INSERT INTO status_usuario (status, id_usuario, data_inicio_afastamento, data_fim_afastamento) 
			VALUES ('" . $statusUsu->status . "', '" . $statusUsu->idUsuario . "', 
			'" . $statusUsu->dataInicioAfastamento . "', '" . $statusUsu->dataFimAfastamento . "')";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
							
			return true;
			
		} catch ( PDOException $e ) {
			return "Erro de banco de dados";
		}
		
	}*/
	
	//deleta pelo id selecionado
	public function deleteId($id) {

		try {
		
			$sql = "DELETE FROM status_usuario WHERE id = '" . $id . "'";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true;
			
		} catch (PDOException $e){
			return "Erro de banco";
		}
		
	}
	
	/* Verificar se o usuario ja tem alguma troca pedente para esta data
	 * Verificar tambem se existe uma folga ja cadastrado para esta data
	 * */
	public function VerificarCadFerias($idUsu, $dataInicio, $dataFim){
		
		$sql = "SELECT u.apelido, u.id, su.data_inicio, su.data_final, ts.data_tira, ts.data_solicitante,
				CASE 
				WHEN ((ts.data_tira BETWEEN '".$dataInicio."' AND '".$dataFim."') OR (ts.data_solicitante BETWEEN '".$dataInicio."' AND '".$dataFim."')) 
				AND (ts.id_usuario_tira = '".$idUsu."' OR ts.id_usuario_solicitante = '".$idUsu."') 
				AND (cts.libera_troca = '' OR cts.libera_troca = 'sim') 
				THEN 'possui_troca_servico' 
				WHEN su.id_usuario = '".$idUsu."' 
				AND su.status = 'valido' 
				AND ('".$dataInicio."' AND '".$dataFim."' BETWEEN su.data_inicio AND su.data_final) 
				THEN 'possui_folga'  
				ELSE 'nao_possui_pendencias' 
				END as valida_usuario 
				FROM usuario u 
				RIGHT JOIN ativacao_usuario au ON(u.id = au.id_usuario) 
				RIGHT JOIN status_usuario su ON(u.id = su.id_usuario) 
				INNER JOIN troca_servico ts ON(u.id = ts.id_usuario_tira OR u.id = ts.id_usuario_solicitante) 
				INNER JOIN comfirmar_troca_servico cts ON(ts.id = cts.id_troca_servico) 
				WHERE (('".$dataInicio."' AND '".$dataFim."' BETWEEN su.data_inicio AND su.data_final)
				OR ((ts.data_tira BETWEEN '".$dataInicio."' AND '".$dataFim."') OR (ts.data_solicitante BETWEEN '".$dataInicio."' AND '".$dataFim."')))
				AND '".$idUsu."' IN (ts.id_usuario_tira, ts.id_usuario_solicitante, su.id_usuario) 
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
	
	//Função que lista as data passada para invalida o status do usuario
	public function VerificarStatusDataPassada($dataInicio){
		
		$sql = "SELECT * FROM status_usuario WHERE data_final <= '".$dataInicio."' AND status LIKE 'valido'";

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
	
	/*/
	public function VerificarCadDispensa($idUsu, $dataDispensa){
		
		$sql = "SELECT * FROM status_usuario WHERE id_usuario = '".$idUsu."' AND data_dispensa = '".$dataDispensa."'";

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
		
	}*/
	/*/
	public function VerificarCadAfastamento($idUsu, $inicioAfastamento, $fimAfastamento){
		
		$sql = "SELECT * FROM status_usuario WHERE id_usuario = '".$idUsu."' AND
		data_inicio_afastamento = '".$inicioAfastamento."' AND data_fim_afastamento = '".$fimAfastamento."'";

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
		
	}*/
	
	//Lista tudo para o controllerIndex.php
	public function ListaStatusUsu($datInicio, $unidade, $setor){
		
		$sql = "SELECT su.id, su.data_inicio, su.data_final, tf.tipo_folgas, tf.cor, su.id_usuario 
				FROM status_usuario su 
				INNER JOIN tipos_folgas tf ON (su.id_tipos_folgas = tf.id) 
                INNER JOIN usuario u ON (su.id_usuario = u.id)
                INNER JOIN setor s ON (u.id_setor = s.id)
                INNER JOIN unidade_trabalho ut ON (u.id_unidade_trabalho = ut.id)
				WHERE su.data_inicio >= '".$datInicio."'  
				AND su.status = 'valido'
				AND s.id = '".$setor."'
				AND ut.id = '".$unidade."'
				ORDER BY su.id";

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
	
	
	//Lista tudo que estiver na tabela
	public function ListaTudo(){
		
		$sql = "SELECT su.id, u.apelido, su.data_inicio, su.data_final, tf.tipo_folgas FROM usuario u 
				INNER JOIN status_usuario su ON(su.id_usuario = u.id)
				INNER JOIN tipos_folgas tf ON(su.id_tipos_folgas = tf.id)
				WHERE tf.status = 'ativado' AND su.status = 'ativado' AND u.status = 'ativado'";

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

	/*/Lista se o status estiver valido
	public function ListaStatusValido(){
		
		$sql = "SELECT * FROM status_usuario WHERE status LIKE 'valido'";

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
		
	}*/
	
	//Lista pelo nome do usuario
	public function ListaPeloNome($apelido){
		
		$sql = "SELECT su.id, su.data_periodo_inicio_ferias, su.data_periodo_fim_ferias, su.data_atestado,
				su.data_dispensa, su.data_inicio_afastamento, su.data_fim_afastamento, u.apelido, su.status
				FROM status_usuario su INNER JOIN usuario u ON(su.id_usuario = u.id) WHERE u.apelido LIKE '".$apelido."%'";

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