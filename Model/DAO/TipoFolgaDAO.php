<?php
include_once 'Conexao/Conexao.php';

class TipoFolgaDAO {

	private $conn = null;
	
	public function cadastrar($nome, $status, $cor) {
	
		try {
			
			$sql = "INSERT INTO tipos_folgas (tipo_folgas, status, cor) VALUES ('" . $nome ."', '" . $status ."', '" . $cor ."')";
			
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

	public function alterar($nome, $id) {
		
		try {
			
			$sql = "UPDATE tipos_folgas SET tipo_folgas='" . $nome . "' WHERE id = '" . $id . "'";
			
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

	public function ListaFolga(){
		
		$sql = "SELECT * FROM tipos_folgas WHERE status LIKE 'ativado'";

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
	
	public function ListaPeloNome($nome){
		
		$sql = "SELECT * FROM tipos_folgas WHERE tipo_folgas LIKE '".$nome."' AND status LIKE 'ativado' ";

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