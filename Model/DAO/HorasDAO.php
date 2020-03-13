<?php
include_once 'Conexao/Conexao.php';

class HorasTrabalhadasDAO {

	private $conn = null;
	
	public function cadastrar(Horas $hora) {
		
			try {
				
				$sql = "INSERT INTO horas (horas_trabalhadas) VALUES ('" . $hora->horas_trabalhadas . "')";
				
				$conn = new Conexao ();
				$conn->openConnect ();
				
				$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
				$resultado = mysqli_query ( $conn->getCon (), $sql );
				
				$conn->closeConnect ();
								
				return true;
				
			} catch ( PDOException $e ) {
				
				echo $e->getMessage();
				return false;
								
			}
			
	}
	
	//Retorna a lista como todos os setores cadastrado
	public function lista(){
		
		$sql = sprintf("SELECT * FROM horas ORDER BY id ASC");

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
	
	//Retorna para o cadastrado se a entrada estiver ja cadastrada
	public function ValidarHora($horas){
	
		$sql = "SELECT horas_trabalhadas FROM horas WHERE horas_trabalhadas = '".$horas."'";
		
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