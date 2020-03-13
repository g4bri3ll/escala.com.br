<?php
include_once 'Conexao/Conexao.php';

class AtivacaoUsuarioDAO {

	private $conn = null;
	
	public function cadastrarAtivacao($codAtivacao, $id, $status) {
		
			try {
				
				$sql = "INSERT INTO ativacao_usuario (codigo_ativacao, estado_ativado, id_usuario) VALUES 
				('" . $codAtivacao . "', '" . $status . "', '" . $id . "')";
				
				$conn = new Conexao ();
				$conn->openConnect ();
				
				$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
				$resultado = mysqli_query ( $conn->getCon (), $sql );
				
				$conn->closeConnect ();
								
				return true;
				
			} catch ( PDOException $e ) {
				return "erro ao cadastrar o codigo de acesso do usuario";
			}
			
	}
	
	//alterar os dados do usuario
	public function AtivaUsuario($status, $idusu) {
		
		try {
			
			$sql = "UPDATE ativacao_usuario SET estado_ativado='" . $status . "' 
					WHERE id_usuario = '" . $idusu . "'";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true;
			
		} catch (PDOException $e) {
			
			return "Erro de banco de dados"; 
			
		}
		
	}
	
	//desativado o usuario
	public function DesativaUsuario($status, $idusu) {
		
		try {
			
			$sql = "UPDATE ativacao_usuario SET estado_ativado='" . $status . "' 
					WHERE id_usuario = '" . $idusu . "'";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true;
			
		} catch (PDOException $e) {
			
			return "Erro de banco de dados"; 
			
		}
		
	}
	
	//Retorna a lista como todos usuarios desativado
	public function ListaUsuDesativado(){
		
		$sql = "SELECT u.id, u.apelido FROM usuario u 
				INNER JOIN ativacao_usuario au ON (au.id_usuario = u.id) 
				WHERE au.estado_ativado = 'desativado'";

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
	
	//lista  todos usuarios ativado
	public function ListaUsuAtivado(){
		
		$sql = "SELECT u.id, u.apelido FROM usuario u 
				INNER JOIN ativacao_usuario au ON (au.id_usuario = u.id) 
				WHERE au.estado_ativado = 'ativado'";

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
	
	//lista  todos usuarios ativado pelo nome
	public function ListaUsuPeloApelido($apelido){
		
		$sql = "SELECT u.id, u.apelido FROM usuario u 
				INNER JOIN ativacao_usuario au ON (au.id_usuario = u.id) 
				WHERE au.estado_ativado = 'ativado' AND u.apelido LIKE '".$apelido."'";

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