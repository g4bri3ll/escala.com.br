<?php
include_once 'Conexao/Conexao.php';

class PassaPlantaoDAO {

	private $conn = null;
	
	public function cadastrar(PassaPlantao $passa) {
		
		try {
			
			$sql = "INSERT INTO passa_plantao (estado_chamado, numero_chamado, foi_feito, deve_fazer, 
			comentarios, id_tecnico_ciente, id_usuario, status, data)	
			VALUES ('" . $passa->estadoChamado . "', '" . $passa->numeroChamado . "', '" . $passa->foiFeito . "', 
			'" . $passa->deveFazer . "', '" . $passa->comentarios . "',	'". $passa->tecnicoCiente ."',	
			'". $passa->idUsuario ."', '". $passa->status ."', '".$passa->data."')";
			
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

	//Verificar se este chamado ja foi cadastrado
	public function VerificaNumChamado($numChamado){
		
		$sql = "SELECT numero_chamado FROM passa_plantao
				WHERE numero_chamado = '".$numChamado."'";

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