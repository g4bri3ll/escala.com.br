<?php
include_once 'Conexao/Conexao.php';

class ChefeSetorDAO {

	private $conn = null;
	
	public function cadastrar($idSetor, $idUsuario, $idFuncaoChefe, $status, $visualizarPainel) {
		
		try {
				
			$sql = "INSERT INTO chefe_setor (id_funcao, id_setor, id_usuario, status, visualizar_painel) VALUES 
			('" . $idFuncaoChefe . "', '" . $idSetor . "', '" . $idUsuario . "', '" . $status . "', '" . $visualizarPainel . "')";
			
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
	
	//
	public function lista(){
		
		$sql = "SELECT * FROM chefe_setor WHERE status = 'ativado'";

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
	
	//Verificar se o setor ja tem chefe cadastrado nele, pra realizar a troca 
	//de servico. trocaservico.php
	public function VerificarChefeCad($idSetorSession){
		
		$sql = "SELECT * FROM chefe_setor 
				WHERE id_setor = '".$idSetorSession."' 
				AND status = 'ativado' LIMIT 1";
		
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
	
	
	//Retorna para o cadastrado se a entrada estiver ja cadastrada
	public function ValidarDados($idUsuario, $idFuncaoChefe, $idSetor){
	
		$sql = "SELECT * FROM chefe_setor 
				WHERE id_usuario = '".$idUsuario."' 
				AND id_funcao = '".$idFuncaoChefe."'
				AND id_setor = '".$idSetor."'";
		
		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$result = mysqli_query($conn->getCon(), $sql); 
		
		$conn->closeConnect ();
		
		//Inicia os array e atribuir ao seus valores
		$array = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$array[]=$row;
		}
		
		return $array; 
		
	}
	
}

?>