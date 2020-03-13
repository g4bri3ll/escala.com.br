<?php

include_once 'Conexao/Conexao.php';

class AcessoPaginasDAO {
	
	private $conn = null;
	
	public function cadastrar(AcessoPaginas $acessoPagina) {
		
		try {
			
			$sql = "INSERT INTO acesso_paginas (nome_paginas, status) VALUES 
			('" . $acessoPagina->paginas . "', '" . $acessoPagina->status . "')";
			
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
	
	//alterar os dados do usuario
	public function alterar(AcessoPaginas $ace) {
		
		try {
			
			$sql = "UPDATE acesso_paginas SET nome_paginas='" . $ace->paginas . "' WHERE id = '" . $ace->id . "'";
			
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
	
	//deleta pelo id selecionado
	public function delete($status, $id) {
		
		try {
			
			$sql = "UPDATE acesso_paginas SET status='" . $status . "'
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
	
	//Retorna a lista como todos os usuarios menos a senha e a comfirmar de senha
	public function listaPaginas(){
		
		$sql = "SELECT * FROM acesso_paginas WHERE status = 'ativado' ORDER BY nome_paginas";
		
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
	
	//Lista todos os acesso a pagina pelo nome do acesso
	public function listaPaginasPeloAcesso($acesso){
		
		$sql = "SELECT ta.id, ta.nome, ap.nome_paginas FROM tipo_acesso ta 
				INNER JOIN id_tipo_acesso_paginas itap ON(ta.id = itap.id_tipo_acesso) 
                INNER JOIN acesso_paginas ap ON(itap.id_acesso_paginas = ap.id) 
				WHERE ta.status LIKE 'ativado' AND ta.nome LIKE '".$acesso."'";
		
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
	
	/*/Lista acesso paginas pelo nome
	public function MostraPaginasPeloNome($nomePagina){
		
		$sql = "SELECT * FROM acesso_paginas 
		WHERE nome_paginas LIKE '".$nomePagina."%' AND status = 'ativado'";
		
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
		
	}*/
	
	
	//Retorna a lista de paginas pelo id da pagina para o cadastrado de paginas de acesso do usuario
	//Para verificar se o usuário ja tem a pagina cadastrada no nome
	/*public function listaPaginasPeloNome($idPaginas){
		
		$sql = sprintf("SELECT nome_paginas FROM acesso_paginas 
		WHERE id = '".$idPaginas."' AND status = 'ativado'");
		
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
		
	}*/
	
	//Retorna a lista como todos os usuarios menos a senha e a comfirmar de senha
	public function listaPaginasPeloID($id){
		
		$sql = sprintf("SELECT * FROM acesso_paginas WHERE id = '".$id."'");
		
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
	
	//Retorna a para o cadastro se a pagina já estiver cadastrada
	public function ValidarCadastro($paginas){
		
		$sql = "SELECT nome_paginas FROM acesso_paginas WHERE nome_paginas LIKE '".$paginas."'";
		
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
	
	//Retorna a para o alterar se a pagina já estiver cadastrada
	public function ValidarDadosParaAltera($paginas, $idPagina){
		
		$sql = "SELECT nome_paginas FROM acesso_paginas WHERE nome_paginas LIKE '".$paginas."' AND id <> '".$idPagina."'";
		
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