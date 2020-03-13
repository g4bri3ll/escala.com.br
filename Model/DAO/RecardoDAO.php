<?php
include_once 'Conexao/Conexao.php';

class RecardoDAO {

	private $conn = null;
	
	public function cadastrar(Recardo $recardo) {
		
		try {

			$sql = "INSERT INTO recardo (comentario, id_estado_chamado, id_usuario_envia, id_usuario_recebe, status, data) 
					VALUES ('" . $recardo->comentario . "', '" . $recardo->idEstadoChamado . "','" . $recardo->idUsuEnvia . "', 
					'" . $recardo->idUsuRecebe . "', '" . $recardo->status . "', '" . $recardo->data . "')";
			
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
	
	//Se o usuario já leu e apertou o botão que ja leu ele muda o status
	public function alteraStatusLido($status, $idRecardo) {
		
		try {
			
			$sql = "UPDATE recardo SET status='" . $status . "'
			 WHERE id = '" . $idRecardo . "'";
			
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
	
	//cancelar 
	public function delete($status, $id) {
		
		try {
			
			$sql = "UPDATE recardo SET status='" . $status . "'
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
	
	//
	public function lista(){
		
		$sql = "SELECT * FROM recardo WHERE status = 'ativado'";

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
	
	/* Lista o recardo deixado pro outro usuario, para o listaRecardoUsuario.php e index.php
	 * Se tiver algum recardo para o usuario ele informar no index e lista na listaRecardoUsuario.php
	 * */
	
	public function listaRercadoPeloUsuario($idSession){
		
		$sql = "SELECT r.id as id_recardo, u.id, r.comentario, u.apelido, r.data FROM recardo r 
				INNER JOIN usuario u ON (r.id_usuario_recebe = u.id) 
				WHERE r.status = 'nao_visto' 
				AND r.id_usuario_recebe = '".$idSession."' LIMIT 1";

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
		
		$sql = "SELECT * FROM recardo 
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