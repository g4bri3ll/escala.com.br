<?php
include_once 'Conexao/Conexao.php';

class SetorDAO {

	private $conn = null;
	
	public function cadastrar(Setor $setor) {
	
		try {
			
			$sql = "INSERT INTO setor (nome_setor, status, id_unidade_trabalho)	VALUES 
			('" . $setor->nome . "', '" . $setor->status . "', '". $setor->idUnidadeTrabalho ."')";
			
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

	//Cadastrar outro setor no usuario
	public function cadastrarOutroSetor($idSubSetor, $idUsuario, $principal, $status) {
	
		try {
			
			$sql = "INSERT INTO setor_usuario (id_sub_setor, id_usuario, principal, status) VALUES 
			('" . $idSubSetor ."', '" . $idUsuario ."', '" . $principal ."', '" . $status ."')";
			
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
	
	public function cadastrarSubSetor($nome, $idEntrada, $idSaida, $status, $estadoDias, $idSetor) {
	
		try {
			
			$sql = "INSERT INTO sub_setor (nome, id_hora_entrada, id_hora_saida, status, 
			estado_dias, id_setor)	VALUES ('" . $nome . "', '" . $idEntrada . "', 
			'" . $idSaida . "',	'" . $status . "', '" . $estadoDias . "',	'". $idSetor ."')";
			
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
	
	public function cadastraNomeDias($idSubSetor, $diasArray, $status) {
		
		try {
			
			$sql = "INSERT INTO id_setor_dias_semanais (id_dias_semanais, id_sub_setor, status)
			VALUES ('" . $diasArray . "', '" . $idSubSetor . "', '" . $status . "')";
			
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
	
	//alterar os dados do setor
	public function alterar(Setor $setor) {
		
		try {
			
			$sql = "UPDATE setor SET nome_setor='" . $setor->nome . "' WHERE id = '" . $setor->id . "'";
			
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
	public function CancelarPeloId($status, $id) {

		try {
		
			$sql = "UPDATE setor SET status='" . $status . "' WHERE id = '" . $id . "'";
			
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
	
	//deativa pelo id selecionado
	public function CancelarPeloIdSubSetor($status, $id) {

		try {
		
			$sql = "UPDATE sub_setor SET status='" . $status . "' WHERE id = '" . $id . "'";
			
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
	
	/*/Retorna a lista como todos os setores cadastrado
	public function lista(){
		
		$sql = "SELECT s.id, s.nome_setor, he.horas_trabalhadas as hora_entrada, 
				hs.horas_trabalhadas as hora_saida FROM setor s 
				INNER JOIN horas he ON(s.id_hora_entrada = he.id)
				INNER JOIN horas hs ON(s.id_hora_saida = hs.id)";

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
	*/
	
	//Lista para o listaSetor
	public function lista(){
		
		$sql = "SELECT s.id, s.nome_setor, ut.nome FROM setor s INNER JOIN unidade_trabalho ut ON(s.id_unidade_trabalho = ut.id) 
				WHERE s.status = 'ativado'";

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
	
	//Lista os sub setores para o index
	public function ListaSubSetor($setor){
		
		$sql = "SELECT ss.nome as nome_sub_setor, ss.id FROM setor s INNER JOIN sub_setor ss ON(s.id = ss.id_setor) 
				WHERE s.status = 'ativado' AND ss.status = 'ativado' AND s.id = '".$setor."'";
		
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
	
	//Lista para o cadastroSubSetor.php
	public function listaComUnidadeTrab($idUnidade){
		
		$sql = "SELECT s.id, ut.nome, s.nome_setor FROM setor s INNER JOIN unidade_trabalho ut ON(s.id_unidade_trabalho = ut.id)
				WHERE ut.id = '".$idUnidade."' AND ut.status = 'ativado' AND s.status LIKE 'ativado'";

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
	
	//Lista sub setor para o listaSubSetor
	public function listSubSetor(){
		
		$sql = "SELECT * FROM sub_setor";

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
	
	//Verificar se o usuario ja tem acesso ao setor
	public function VerificarSetorCadNoUsuario($idSubSetor, $idUsuario){
		
		$sql = "SELECT * FROM setor_usuario WHERE id_sub_setor = '".$idSubSetor."' 
				AND id_usuario = '".$idUsuario."' AND principal <> '0' AND status = 'ativado'";

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
	
	/*/Lista para o cadastroOutroSetor
	public function listaSetorPeloUsuario($idUnidade){
		
		$sql = "SELECT s.id, s.nome_setor FROM setor s INNER JOIN unidade_trabalho ut ON(s.id_unidade_trabalho = ut.id) 
				WHERE s.status LIKE 'ativado' AND ut.status LIKE 'ativado' AND ut.id = '".$idUnidade."'";

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
		
	}/*/
	
	//Lista para cadastrar a funcao do usuario no setor
	public function ListSetor($idUnidade){
		
		$sql = "SELECT * FROM setor WHERE status LIKE 'ativado' AND id_unidade_trabalho = '".$idUnidade."'";

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
	
	//Lista os dias que o usuario vai trabalhar para ControllerCadDataUsuario.php
	public function ListaNomeDiaSemana($idSetor){
		
		$sql = "SELECT dse.id, ds.nome_ingles, dse.id_dias_semanais FROM dias_semanais ds 
				INNER JOIN id_setor_dias_semanais isds ON(isds.id_dias_semanais= ds.id)
				INNER JOIN data_semanais dse ON(dse.id_dias_semanais = ds.id) 
				INNER JOIN setor s ON(isds.id_setor = s.id) 
				WHERE isds.id_setor = '".$idSetor."'";

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
	
	
	//Lista para o listaSetor.php
	public function BuscaPeloSetor($nome){
		
		$sql = "SELECT s.id, s.nome_setor, ut.nome FROM setor s INNER JOIN unidade_trabalho ut ON(s.id_unidade_trabalho = ut.id) 
				WHERE s.status = 'ativado' AND s.nome_setor LIKE '".$nome."' AND ut.status = 'ativado'";

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
	
	//Retorna o ultimo cadastrado efetuado no setor para o ControllerCadNomeDiaSetor
	public function ListaUltimoCad($nome, $idSetor){
		
		$sql = "SELECT id FROM sub_setor WHERE nome LIKE '".$nome."' AND id_setor = '".$idSetor."'";

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
	
	//Retorna o estado_dias para o ControlleCadDataUsuario.php
	public function ListEstadoDiasSubSetor($idSubSetor){
		
		$sql = "SELECT ss.nome as sub_setor, ss.id as id_sub_setor, ds.nome, ds.nome_ingles, ss.estado_dias 
				FROM sub_setor ss 
				LEFT JOIN id_setor_dias_semanais isds ON(ss.id = isds.id_sub_setor) 
				LEFT JOIN dias_semanais ds ON(isds.id_dias_semanais = ds.id) 
				WHERE ss.id = '".$idSubSetor."'";

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
	
	//Verificar se o dia e igual ao do setor cadastrar para o ControlleCadDataUsuario.php
	public function VerificarNomeInglesSubSetor($nomeIngles, $idSubSetor){
		
		$sql = "SELECT ss.nome as sub_setor, ss.id as id_sub_setor, ds.nome, ds.nome_ingles 
				FROM sub_setor ss 
				INNER JOIN id_setor_dias_semanais isds ON(ss.id = isds.id_sub_setor) 
				INNER JOIN dias_semanais ds ON(isds.id_dias_semanais = ds.id) 
				WHERE ss.id = '".$idSubSetor."' AND ds.nome_ingles LIKE '".$nomeIngles."'";
		
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
	
	//Retorna a lista como todos os setores cadastrado
	public function ListaPeloId($id){
		
		$sql = "SELECT ut.id as id_unidade, s.id, s.nome_setor, ut.nome FROM setor s 
				INNER JOIN unidade_trabalho ut ON(s.id_unidade_trabalho = ut.id)			
				WHERE s.id = '".$id."'";

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
	
	//Verifiacar se o setor ja esta cadastrado
	public function VerificarNomeCad($nome, $idUnidadeTrabalho){
		
		$sql = "SELECT * FROM setor 
				WHERE nome_setor LIKE '".$nome."' AND id_unidade_trabalho = '".$idUnidadeTrabalho."'";

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
	
	//Verifiacar se o sub setor ja esta cadastrado para o cadastro do sub setor
	public function VerificarNomeSubSetor($nome, $idSetor){
		
		$sql = "SELECT * FROM sub_setor WHERE nome LIKE '".$nome."' AND id_setor = '".$idSetor."'";
		
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
	
	public function BuscaDadosParaAlterar($nome, $idUni){
		
		$sql = "SELECT ut.id as id_unidade, s.id, s.nome_setor, ut.nome FROM setor s
				INNER JOIN unidade_trabalho ut ON(s.id_unidade_trabalho = ut.id)
				WHERE s.nome_setor LIKE '".$nome."' 
				AND ut.id = '".$idUni."'";

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
	
	public function BuscarPeloNomeSubSetor($nome){
		
		$sql = "SELECT s.id, s.nome, he.horas_trabalhadas as hora_entrada, 
				hs.horas_trabalhadas as hora_saida FROM sub_setor s 
				INNER JOIN horas he ON(s.id_hora_entrada = he.id)
				INNER JOIN horas hs ON(s.id_hora_saida = hs.id) 
				WHERE nome LIKE '".$nome."%'";

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