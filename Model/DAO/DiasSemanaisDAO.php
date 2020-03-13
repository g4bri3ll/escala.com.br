<?php

include_once 'Conexao/Conexao.php';
include_once 'Validacoes/ValidarDiasSemanais.php';

class DiasSemanaisDAO {

	private $conn = null;
	
	/*/public function cadastrar() {

		$diaSemana = new ValidarDiasSemanais();
		$arraySemana = $diaSemana->retornaSemana();
		
		for ($i = 0;$i < count($arraySemana['nome']);$i++){
			
			$sql = "INSERT INTO dias_semanais (nome, nome_ingles) 
			VALUES ('" . $arraySemana['nome'][$i] . "', '" . $arraySemana['sigla'][$i] . "')";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
		
		}
		header("Location: index.php");
		
	}*/
	
	//Retorna a lista como todos os setores cadastrado
	public function lista($arrayData){
		
		$arrayNome = array();
		//Tem que fazer um contado de numeros para colocar no array as data 
		//para mostrar na tela inicial do index
		for ($i = 0; $i < count($arrayData); $i++){
				
			$sql = "SELECT DISTINCT(ds.nome), dse.data FROM dias_semanais ds 
					INNER JOIN data_semanais dse ON(ds.id = dse.id_dias_semanais) 
					WHERE dse.data = '".$arrayData[$i]."'";
			
			$conn = new Conexao();
			$conn->openConnect();
			
			$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
			$resultado = mysqli_query($conn->getCon(), $sql);
			
			$conn->closeConnect ();
			
			
			$array = array();
			while ($row = mysqli_fetch_assoc($resultado)) {
				$array[]=$row;
			}
			$arrayNome[$i] = $array;
		}
		
		return $arrayNome;
		
	}
	
	/*/Verificar data cadastrada para o index.php
	public function VerificarVazio(){
		
		$sql = "SELECT * FROM dias_semanais LIMIT 7";
		
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
	*/
	
	//Retorna para o cadastrado se a entrada estiver ja cadastrada
	public function ValidarDiaSemana($diaSemana){
	
		$sql = "SELECT nome FROM dias_semanais WHERE nome = '".$diaSemana."'";
		
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
	
	public function ListaParaCadSetor(){
	
		$sql = "SELECT id, nome FROM dias_semanais ORDER BY id ASC";
		
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
	
	//Retorna a lista de id dos dias
	public function listaId($nomeIngles){
		
		$sql = "SELECT id FROM dias_semanais WHERE nome_ingles LIKE '".$nomeIngles."'";

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