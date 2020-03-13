<?php
/*Informar os dias e horas que serão realizados o cadastro da passagem de plantão
 * 
 * */

include_once 'Conexao/Conexao.php';

class TipoAcessoDAO {

	private $conn = null;
	
	public function cadastrar(TipoAcesso $tipoAcesso) {

		try {
			
			$sql = "INSERT INTO tipo_acesso (nome, status) 
					VALUES ('" . $tipoAcesso->nome . "', '" . $tipoAcesso->status . "')";
			
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
	
	//Cadastrar as paginas no tipo de acesso
	public function CadastraTipoAcessoPaginas($idTipo, $idPaginas) {

		try {
			
			$sql = "INSERT INTO id_tipo_acesso_paginas (id_tipo_acesso, id_acesso_paginas) 
					VALUES ('" . $idTipo . "', '" . $idPaginas . "')";
			
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
	
	//deleta pelo id selecionado
	public function delete($status, $id) {
		
		try {
			
			$sql = "UPDATE tipo_acesso SET status='" . $status . "'
			 WHERE id = '" . $id . "'";
			echo $sql;
			$conn = new Conexao ();
			$conn->openConnect ();
			
			mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true;
			
		} catch (PDOException $e){
			return "erro de banco de dados";
		}
		
	}
	
	//Lista tudo que estiver na tabela
	public function ListaTudo(){
		
		$sql = "SELECT * FROM tipo_acesso";

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
	
	//Lista para o usuario efetuar o cadastro no banco de dados
	public function ListaParaUsuario(){
		
		$sql = "SELECT DISTINCT(nome), id FROM tipo_acesso WHERE status = 'ativado' GROUP BY nome";

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
	
	//Lista o nome se estiver cadastrado e tem que esta ativado tambem, para o cadastrado
	public function VerificarNomeCadastro($nome){
		
		$sql = "SELECT * FROM tipo_acesso WHERE nome LIKE '".$nome."' AND status LIKE 'ativado'";

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
	
	//Lista todos os acesso com as permissoes concedidas
	public function Lista(){
		
		$sql = "SELECT id, nome FROM tipo_acesso WHERE status LIKE 'ativado'";

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
	
	//Retorna o ultimo id cadastrado para o cadastradoTipoAcesso.php
	public function RetornaUltimoID($nome){
		
		$sql = "SELECT id FROM tipo_acesso WHERE nome LIKE '".$nome."' AND status LIKE 'ativado'";

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
	
	//Lista acesso paginas pelo nome
	public function MostraPaginasPeloNome($nomePagina){
		
		$sql = "SELECT id, nome FROM tipo_acesso WHERE status LIKE 'ativado' AND nome LIKE '".$nomePagina."%'";
		
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