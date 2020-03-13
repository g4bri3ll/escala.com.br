<?php
include_once 'Conexao/Conexao.php';

class ComfirmarTrocaServicoDAO {

	private $conn = null;
	
	public function cadastra($idQuemTira, $idQuemSolicitou, $pedente, $idTrocaServico, $status) {
		
		try {
			
			$sql = "INSERT INTO comfirmar_troca_servico (id_quem_tira, id_quem_solicitou, pendente, id_troca_servico, status) 
					VALUES ('" . $idQuemTira . "',	'" . $idQuemSolicitou . "',	'" . $pedente . "', '" . $idTrocaServico . "', '" . $status . "')";
			
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
	
	//O outro colaborador que ira tira o servico faz a comfirmação da troca aqui
	public function ComfirmarTrocaQuemTira($id, $comfirmar, $motivo) {
		
		try {
			
			$sql = "UPDATE comfirmar_troca_servico SET libera_troca_quem_tira = '".$comfirmar."',
			 		motivo_quem_tira = '".$motivo."' WHERE id = '".$id."'";
			
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
	
	/* Verificar se o chefe ou supervisor do setor tem alguma troca de servico para autorizar
	 * Se tiver ele lista na tela tanto no AutorizaTrocaServico.php como index.php
	 * */
	public function AutorizarTrocaServico($idUsuario, $idSetor, $data) {
		
		try {
			
			$sql = "SELECT cts.id, uqt.apelido as usu_quem_tira, uqs.apelido as usu_quem_solicita, ts.data_tira, ts.data_solicitante 
					FROM comfirmar_troca_servico cts 
					INNER JOIN troca_servico ts ON(cts.id_quem_tira = ts.id_usuario_tira) 
					INNER JOIN usuario uqt ON(ts.id_usuario_tira = uqt.id) 
					INNER JOIN usuario uqs ON(ts.id_usuario_solicitante = uqs.id)
					INNER JOIN setor s ON(uqt.id_setor = s.id) 
					INNER JOIN chefe_setor cs ON(s.id = cs.id_setor) 
					WHERE cs.id_setor = '".$idSetor."' 
					AND cs.id_usuario = '".$idUsuario."' 
					AND cs.status = 'ativado' 
					AND ts.data_tira > '".$data."' 
					AND cts.status LIKE 'nao_verificado' 
					AND cts.libera_troca_quem_tira LIKE 'comfirmada'
					GROUP BY cts.id, uqt.apelido, uqs.apelido, ts.data_tira, ts.data_solicitante";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			$array = array();
			
			while ($row = mysqli_fetch_assoc($resultado)) {
				$array[]=$row;
			}
			return $array;
			
		} catch ( PDOException $e ) {
			return "Erro de banco de dados";
		}
			
	}
	
	//Libera e permitir as trocas entres os funcionarios para o AutorizaTrocaServico.php, liberaTrocaServico.php
	public function LiberaTrocaChefe($liberaTroca, $pendente, $id, $idChefe, $status){
		
		try {

			$sql = "UPDATE comfirmar_troca_servico SET id_chefe_libera = '".$idChefe."', 
					libera_troca = '".$liberaTroca."', status = '".$status."', pendente = '".$pendente."' 
					WHERE id = '".$id."'";
			
			$conn = new Conexao();
			$conn->openConnect();
			
			$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
			$resultado = mysqli_query($conn->getCon(), $sql);
			
			$conn->closeConnect ();
			
			return true;
			
		} catch ( PDOException $e ) {
			return "Erro de banco de dados";
		}
	}

	//Aqui o chefe cancelar a troca do servico
	public function CancelarTrocaChefe($comfirmarcao, $id, $idChefe, $status, $comfirmarQuemTira){
		
		try {
			
			$sql = "UPDATE comfirmar_troca_servico SET id_chefe_libera = '".$idChefe."', 
					libera_troca = '".$comfirmarcao."', status = '".$status."', 
					libera_troca_quem_tira = '".$comfirmarQuemTira."' 
					WHERE id = '".$id."'";
			
			$conn = new Conexao();
			$conn->openConnect();
			
			$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
			$resultado = mysqli_query($conn->getCon(), $sql);
			
			$conn->closeConnect ();
			
			return true;
			
		} catch ( PDOException $e ) {
			return "Erro de banco de dados";
		}
	}
	
	//Cancelar a troca o outro funcionario que foi solicitado
	public function CancelarTrocaUsuario($comfirmarUsu, $comfirmarChefe, $motivo, $status, $id){
		
		try {
			
			$sql = "UPDATE comfirmar_troca_servico SET libera_troca = '".$comfirmarUsu."', 
					libera_troca_quem_tira = '".$comfirmarChefe."', motivo_quem_tira = '".$motivo."', 
					status = '".$status."' WHERE id = '".$id."'";
			
			$conn = new Conexao();
			$conn->openConnect();
			
			$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
			$resultado = mysqli_query($conn->getCon(), $sql);
			
			$conn->closeConnect ();
			
			return true;
			
		} catch ( PDOException $e ) {
			return "Erro de banco de dados";
		}
	}
	
	//Esta verificando se o usuario ja o supervisor ja fez a troca do servico
	public function ConcordaTrocaServico($id){
		
		$sql = "SELECT u.nome_usuario FROM usuario u 
				INNER JOIN troca_servico ts ON (u.id = ts.id_usuario_tira) 
				INNER JOIN comfirmar_troca_servico cts ON (ts.id = cts.id_troca_servico) 
				WHERE cts.id = '".$id."' 
				AND cts.libera_troca_quem_tira = '' 
				AND cts.status = 'nao_verificado'";
		
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
	
	//Lista todas as trocas pedente para o chefe liberar a troca LiberaTrocaServico.php
	public function ListaTrocasPedentes(){
		
		$sql = "SELECT us.nome_usuario as nome_solicita, ut.nome_usuario as nome_tira, 
				ts.data_solicitante, ts.data_tira, ts.motivo_troca, cts.id 
				FROM troca_servico ts 
				INNER JOIN usuario us ON (ts.id_usuario_solicitante = us.id) 
				INNER JOIN usuario ut ON (ts.id_usuario_tira = ut.id)
				INNER JOIN comfirmar_troca_servico cts ON (ts.id = cts.id_troca_servico) 
				WHERE cts.id_chefe_libera = 0 
				AND cts.libera_troca = '' 
				AND cts.status LIKE 'nao_verificado'";

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