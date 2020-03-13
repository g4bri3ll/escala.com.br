<?php

include_once 'Conexao/Conexao.php';

class FuncaoChefeSetorDAO {

	private $conn = null;
	
	public function cadastrar($nome, $status) {

		try {
			
			$sql = "INSERT INTO funcao_chefe_setor (nome, status) 
			VALUES ('" . $nome . "', '" . $status . "')";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
							
			return true;
			
		} catch ( PDOException $e ) {
			return "erro de banco de dados";
		}
		
	}
	
	//
	public function lista(){
		
		$sql = "SELECT * FROM funcao_chefe_setor WHERE status LIKE 'ativado'";

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
	
	//Verificar o nome para o cadatro para ver se ele ja esta cadastrado 
	public function VerificarNome($nome){
		
		$sql = "SELECT * FROM funcao_chefe_setor 
				WHERE nome LIKE '".$nome."' AND status LIKE 'ativado'";

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