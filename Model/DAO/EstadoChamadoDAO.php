<?php
include_once 'Conexao/Conexao.php';

class EstadoChamadoDAO {

	private $conn = null;
	
	public function cadastrar($nome, $status) {
		
		try {
				
			$sql = "INSERT INTO estado_chamado (nome, status) VALUES 
			('" . $nome . "', '" . $status . "')";
			
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
	
	//cancelar 
	public function delete($status, $id) {
		
		try {
			
			$sql = "UPDATE estado_chamado SET status='" . $status . "'
			 WHERE id = '" . $id . "'";
			
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
	
	//lista para o recardo.php e passaPlantao.php
	public function lista(){
		
		$sql = "SELECT * FROM estado_chamado WHERE status = 'ativado'";
		
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
	
	//Retorna a lista pelo nome
	public function VerificarCadastro($nome){
		
		$sql = "SELECT * FROM estado_chamado 
		WHERE status = 'ativado' AND nome LIKE '".$nome."'";

		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$resultado = mysqli_query($conn->getCon(), $sql);
		
		$array = array();
		
		while ($row = mysqli_fetch_assoc($resultado)) {
			$array[]=$row;
		}
		
		$conn->closeConnect ();
		return $array;
		
	}
	
}

?>