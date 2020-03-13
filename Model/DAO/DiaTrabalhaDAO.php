<?php

include_once 'Conexao/Conexao.php';
include_once 'Validacoes/ValidarDiasSemanais.php';

class DiaTrabalhaDAO {

	private $conn = null;
	
	public function cadastrar($idUsuario, $idSubSetor, $dataInicio, $dataFinal, $status) {

		try {
		
			$sql = "INSERT INTO dia_trabalha (data_inicio, data_final, status, id_usuario, id_sub_setor) 
					VALUES ('" . $dataInicio . "', '" . $dataFinal . "', '" . $status . "', '" . $idUsuario . "', '" . $idSubSetor . "')";
			
			$conn = new Conexao ();
			$conn->openConnect ();
				
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true; 
				
		} catch ( PDOException $e ) {
			return "erro ao cadastrar";
		}
		
	}
	
	//Retorna a lista de todos os dia trabalhado
	public function listaComUsuario(){
		
		$sql = "SELECT u.apelido, u.id as id_usuario, dt.id, dt.data_inicio, dt.data_final 
				FROM dia_trabalha dt INNER JOIN usuario u ON(dt.id_usuario = u.id)
				WHERE dt.status = 'ativado'";
		
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
	
	//alterar o status do dia trabalho se estiver ativado
	public function AlteraDiaTrabalho($id) {
		
		try {
			
			$sql = "UPDATE dia_trabalha SET status='desativado' WHERE id = '" . $id . "'";
			
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
	
	//Retorna a lista de todos os dia trabalhado
	public function lista(){
		
		$sql = "SELECT * FROM dia_trabalha WHERE status = 'ativado'";
		
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
	
	//Lista pelo nome do usuario
	public function ListPeloNomeUsuario($nome){
		
		$sql = "SELECT u.apelido, u.id as id_usu, dt.id as id_dia 
				FROM dia_trabalha dt INNER JOIN usuario u ON(dt.id_usuario = u.id)
				WHERE u.apelido LIKE '".$nome."%'";
		
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
	
	//Retorna a lista de todos os dia trabalhado
	public function DesativaDia($id, $status){
		
		try {
			
			$sql = "UPDATE dia_trabalha SET status = '".$status."' WHERE id = '".$id."'";
			
			$conn = new Conexao();
			$conn->openConnect();
			
			$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
			$resultado = mysqli_query($conn->getCon(), $sql);
			
			$conn->closeConnect ();
			
			return true; 
			
		} catch ( PDOException $e ) {
			return "erro ao cadastrar";
		}
		
		
	}
	
	//Verificar se o dia trabalho e menor que a data de hoje
	public function VerificarDiaTrabalhoDataPassada($dataInicio){
		
		$sql = "SELECT * FROM dia_trabalha WHERE data_final <= '".$dataInicio."' AND status LIKE 'ativado'";

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
	
	//Retorna a lista de todos os dia trabalhado
	public function VerificaCadastro($idUsuario, $dataInicio, $dataFinal, $status){
		
		$sql = "SELECT * FROM dia_trabalha WHERE id_usuario = '".$idUsuario."' 
				AND data_inicio = '".$dataInicio."' AND data_final = '".$dataFinal."'
				AND status = '".$status."'";
		
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