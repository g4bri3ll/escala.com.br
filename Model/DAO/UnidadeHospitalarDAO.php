<?php
include_once 'Conexao/Conexao.php';

class UnidadeHospitalarDAO {

	private $conn = null;
	
	public function cadastrar(UnidadeHospitalar $uniHospitalar) {
		
		try {
				
			$sql = "INSERT INTO unidade_trabalho (nome, status) VALUES 
			('" . $uniHospitalar->nome . "', '" . $uniHospitalar->status . "')";
			
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
	
	//Cadastrar outra unidade de trabalho
	public function cadastraOutraUnidade($idUsuario, $idUnidade) {
		
		try {
				
			$sql = "INSERT INTO id_unidade_trabalho_usuario (id_usuario, id_unidade_trabalho) 
					VALUES ('" . $idUsuario . "', '" . $idUnidade . "')";
			
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
	
	
	//alterar os dados do usuario
	public function alterar(UnidadeHospitalar $uniHospitalar) {
		
		try {
			
			$sql = "UPDATE unidade_trabalho SET nome='" . $uniHospitalar->nome . "'
			 WHERE id = '" . $uniHospitalar->id . "'";
			
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
	
	//cancelar a unidade
	public function delete($status, $id) {
		
		try {
			
			$sql = "UPDATE unidade_trabalho SET status='" . $status . "'
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
	
	
	//Retorna a lista como todos os dados, index.php, cadastroSetor.php,
	public function lista(){
		
		$sql = "SELECT * FROM unidade_trabalho WHERE status = 'ativado'";

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
	public function listaParaAlterar($id){
		
		$sql = "SELECT * FROM unidade_trabalho 
		WHERE id = '".$id."'";

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
	
	//Retorna a lista pelo nome
	public function ListaPeloNome($nome){
		
		$sql = "SELECT * FROM unidade_trabalho 
		WHERE nome LIKE '".$nome."%' AND status = 'ativado'";

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
	
	//Verificar se o usuario ja tem a unidade cadastrada no usuario dele
	public function ValidaUnidadeCadastro($idUnidade, $idUsuario){
		
		$sql = "SELECT * FROM id_unidade_trabalho_usuario 
		WHERE id_usuario = '".$idUsuario."' AND id_unidade_trabalho = '".$idUnidade."'";

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
	public function ValidarDados($unidade){
	
		$sql = "SELECT nome FROM unidade_trabalho WHERE nome = '".$unidade."'";
		
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
	
	//Retorna para o alterar se a entrada estiver ja cadastrada
	public function ValidarDadosParaAlterar($nome, $id){
	
		$sql = "SELECT nome FROM unidade_trabalho WHERE 
		nome = '".$nome."' AND id <> '".$id."'";
		
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