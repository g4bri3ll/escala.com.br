<?php

include_once 'Conexao/Conexao.php';

class DataSemanaisDAO {

	private $conn = null;

	public function cadastrar($dataCadastro, $id) {
			
		$sql = "INSERT INTO data_semanais (data, id_dias_semanais)
			VALUES ('" . $dataCadastro . "', '" . $id . "')";
			
		$conn = new Conexao ();
		$conn->openConnect ();
			
		$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
		$resultado = mysqli_query ( $conn->getCon (), $sql );
			
		$conn->closeConnect ();

		return true;

	}
	
	//Retorna a data se estiver cadastrada
	public function RetornaData($dataCadastro){
	
		$sql = "SELECT data FROM data_semanais WHERE data LIKE '".$dataCadastro."'";
		
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

	//Retorna a ultima data cadastrada
	public function RetornaUltimaData(){
	
		$sql = "SELECT MAX(data) as data FROM data_semanais GROUP BY data LIMIT 1";
		
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
	
	//Retorna o array de data para o ControllerCadastrarDataUsuario.php
	/*/Se o usuario nao estiver data cadastrada nele
	public function RetornaIdDataMaior($idUltimaDataCadUsu){
	
		$sql = "SELECT id FROM data_semanais WHERE id > '".$idUltimaDataCadUsu."'";
		
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
		
	}*/
	
	/*/Retorna para o index se a data estiver cadastrada ou nï¿½o
	public function RetornaUltimaData($dataFrente){
	
		$sql = "SELECT data FROM data_semanais WHERE data LIKE '".$dataFrente."'";
		
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
		
	}*/
	
	/*
	//Receber a data do dia que o usuario se cadastrado para lista os id das datas atuais
	public function ListaTodosId($data){
	
		$sql = "SELECT id, data FROM data_semanais WHERE data > '".$data."'";
		
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
		
	}*/
	
	//Lista as datas que ainda nao foram cadastrada nos usuarios
	public function ListaDatasParaCadastrada($UltimaData, $dataFrente){
	
		$sql = "SELECT ds.id, dias.nome_ingles, dias.nome FROM data_semanais ds 
				INNER JOIN dias_semanais dias ON(ds.id_dias_semanais = dias.id)
				WHERE ds.data BETWEEN '".$UltimaData."' AND '".$dataFrente."'";
		
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
	
	//Lista os sete dias da semana para o index.php
	public function ListaDiasSemanais($dataInicio, $dataFim){
	
		$sql = "SELECT dis.nome, ds.data FROM data_semanais ds 
				INNER JOIN dias_semanais dis 
				ON (ds.id_dias_semanais = dis.id)
				WHERE ds.data >= '".$dataInicio."'
				AND ds.data <= '".$dataFim."'";
		
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
	
	//Retorna a data para o cadastrado do usuario no banco de dados ControllerListaIndex.php
	/*public function ListaIdPelaData($data, $dataFrente){
	
		$sql = "SELECT ds.id, et.estado FROM data_semanais ds 
				INNER JOIN estado_trabalhado et ON(ds.id = et.id_data_semanais)
				WHERE data BETWEEN '".$data."' AND '".$dataFrente."'";
		
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
		
	}*/

	//Retorna as datas para o fazer o primeiro cadastro do usuario no sistema não mexer e não modificar ela
	//ControllerCadDatausuario.php
	public function ListaDataCadastro($dataMenor, $dataFrente){
	
		$sql = "SELECT ds.id, dse.nome_ingles, ds.data 
				FROM data_semanais ds LEFT JOIN dias_semanais dse ON(ds.id_dias_semanais = dse.id) 
				WHERE data BETWEEN '".$dataMenor."' AND '".$dataFrente."' ORDER BY ds.id";
		
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