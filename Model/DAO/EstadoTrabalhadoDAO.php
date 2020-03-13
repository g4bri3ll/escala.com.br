<?php

include_once 'Conexao/Conexao.php';

class EstadoTrabalhadoDAO {

	private $conn = null;
	
	
	public function cadastrar(EstadoTrabalhado $estTra) {

		try {
			
			$sql = "INSERT INTO estado_trabalhado (estado, id_usuario, id_data_semanais, id_sub_setor) 
					VALUES ('" . $estTra->estado . "', '" . $estTra->idUsuario . "', 
					'" . $estTra->id_data_semanais . "', '" . $estTra->idSubSetor . "')";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true; 
			
		} catch ( PDOException $e ) {
			return "erro ao cadastrar a data do usuario. ID DA DATA: ";
		}
			
	}
	
	//Retorna a ultima data cadastrada e o id do usuario para ControllerCadDiasTrabalhados.php
	public function listaData(){
		
		$sql = "SELECT P.* from
		(
		    SELECT id_usuario, MAX(id_data_semanais) AS id_data_semanais FROM estado_trabalhado
		    GROUP BY id_usuario
		) D
		JOIN estado_trabalhado P
		ON P.id_usuario = D.id_usuario
		AND id_data_semanais = id_data_semanais
		";

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
	
	//Lista todos os dados para ControllerListaIndex.php
	public function ListaTudo($dataAumentada){
	
		$sql = "SELECT * FROM estado_trabalhado WHERE data_trabalhada > '".$dataAumentada."'";
		
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
	
	/*/Lista o estado do usaurio para cadastrar para ControllerCadastrarDataUsuario.php
	public function ListaEstadoPeloIdUsu($idUsu){
	
		$sql = "SELECT id, estado FROM estado_trabalhado WHERE id_usuario = '".$idUsu."' ORDER BY id DESC LIMIT 1";
		
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
	*/
	
	//Lista todos os id dos usaurios e das datas cadastrado nesta tabela para o controllerCadastrarDataUsuario.php
	public function RetornUltimosIds(){
	
		$sql = "SELECT subs.estado_dias, subs.id as id_sub_setor, subs.nome as nome_sub_setor, 
				u.apelido, et.id_usuario, et.estado, ds.id, MAX(ds.data) as data
				FROM sub_setor subs
				INNER JOIN estado_trabalhado et ON(subs.id = et.id_sub_setor) 
				INNER JOIN data_semanais ds ON(et.id_data_semanais = ds.id)
				INNER JOIN usuario u ON(et.id_usuario = u.id) 
				INNER JOIN ativacao_usuario au ON(u.id = au.id_usuario) 
				WHERE au.estado_ativado = 'ativado' 
                GROUP BY u.id, subs.nome";
		
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
	
	/*
	//Receber o id do usuario 
	public function RetornaEstadoCad($id){
	
		$sql = "SELECT id, estado FROM estado_trabalhado 
		WHERE id_usuario = '".$id."' ORDER BY id DESC LIMIT 1";
		
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
	*/
	
}

?>