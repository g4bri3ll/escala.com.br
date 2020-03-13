<?php
if (!isset($_SESSION)) {
	session_start();
}

include_once 'Conexao/Conexao.php';

class UsuarioDAO {

	private $conn = null;
	
	public function cadastrar(Usuario $usuario) {
	
		try {
				
			$senha = md5 ( $usuario->senha );
			$comfirmar_senha = md5 ( $usuario->comfirmar_senha );
			
			$sql = "INSERT INTO usuario (nome_usuario, cpf, senha, comfirmar_senha, 
			email, id_setor, apelido, id_unidade_trabalho, id_tipo_acesso, status)
			VALUES ('" . $usuario->nome . "', '" . $usuario->cpf . "', '" . $senha . "', '" . $comfirmar_senha . "', 
			'" . $usuario->email . "',	'" . $usuario->idSetor . "', '" . $usuario->apelido . "', 
			'" . $usuario->idUnidadeTrabalho . "', '" . $usuario->idTipoAcesso . "', '" . $usuario->status . "')";
			
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

	public function CadastrarFuncaoUsuario(Usuario $usuario) {
	
		try {
			
			$sql = "INSERT INTO usuario_funcao (cidade, bairro, complemento, cep, 
			codigo_ativacao_usuario, opcao_visualizar_painel, id_funcao_exercida, data_cadastro, status)
			VALUES ('" . $usuario->cidade . "', '" . $usuario->bairro . "', '" . $usuario->complemento . "',	
			'" . $usuario->cep . "', '" . $usuario->codigoUsu . "', '" . $usuario->opcaoVisualizarPainel . "',  
			'" . $usuario->idFuncaoExercida . "', '" . $usuario->dataCadastro . "', '" . $usuario->status . "')";
			
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
	
	/*public function cadastraPaginaAcesso($idPaginas, $Idusu) {
		
		try {
			
			$sql = "INSERT INTO ids_usuarios_acesso_paginas (id_usuarios, id_tipo_acesso)
			 VALUES	('".$Idusu."', '".$idPaginas."')";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
							
			return true;
			
		} catch ( PDOException $e ) {
			return "erro de banco de dados";
		}
			
	}*/
	
	//alterar os dados do usuario
	public function alterar(Usuario $usu) {
		
		try {
			
			$sql = "UPDATE usuario SET nome_usuario='" . $usu->nome . "', apelido='" . $usu->apelido . "', 
			email='" . $usu->email . "' WHERE id = '" . $usu->id . "'";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true;
			
		} catch (PDOException $e) {
			
			return "Erro de banco";
			
		}
		
	}

	//alterar a unidade de trabalho do usuario
	public function alterarUnidadeTrabalho($idUsuario, $idUnidade, $idSetor) {
		
		try {
			
			$sql = "UPDATE usuario SET 	id_unidade_trabalho='" . $idUnidade . "', id_setor = '".$idSetor."' 
			WHERE id = '" . $idUsuario . "'";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			mysqli_select_db ( $conn->getCon (), $conn->getBD());
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true;
			
		} catch (PDOException $e) {
			
			return "Erro de banco"; 
			
		}
		
	}
	
	/*/
	public function ListaParaIndexComUnidade($dataListada, $unidade, $setor){
	
		$sql = "SELECT dse.data, et.estado, u.apelido, he.horas_trabalhadas as hora_entrada, 
			hs.horas_trabalhadas as hora_saida, au.estado_ativado, ds.nome_ingles 
			FROM usuario u 
			INNER JOIN estado_trabalhado et ON (u.id = et.id_usuario) 
			INNER JOIN data_semanais dse ON (et.id_data_semanais = dse.id) 
			INNER JOIN dias_semanais ds ON (dse.id_dias_semanais = ds.id) 
			INNER JOIN ativacao_usuario au ON (au.id_usuario = u.id) 
			INNER JOIN setor s ON (u.id_setor = s.id) 
			INNER JOIN unidade_trabalho ut ON (ut.id = s.id_unidade_trabalho)
			INNER JOIN horas he ON (he.id = s.id_hora_entrada)  
			INNER JOIN horas hs ON (hs.id = s.id_hora_saida) 
			WHERE dse.data = '".$dataListada."' 
			AND au.estado_ativado = 'ativado'
			AND u.id_unidade_trabalho = '".$unidade."'
			AND s.id = '".$setor."'";
		echo $sql;
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
		
	}*/
	
	//Lista Para i index.php
	public function ListaIndex($dataInicio, $dataFim, $unidade, $setor){
	
		$sql = "SELECT u.apelido, et.estado, dse.data, u.id, au.estado_ativado, ds.nome_ingles, ds.nome, he.horas_trabalhadas as hora_entrada, hs.horas_trabalhadas as hora_saida, subs.nome as nome_sub_setor, subs.nome as nome_sub_setor, 
				CASE
				WHEN u.id = su.id_usuario AND (dse.data BETWEEN su.data_inicio AND su.data_final) AND su.status = 'valido' THEN tf.cor 
				ELSE 'nao' 
				END as 'cores' ,
				 CASE
				WHEN u.id = dt.id_usuario AND (dse.data BETWEEN dt.data_inicio AND dt.data_final) AND dt.status = 'ativado' THEN 'dia_trabalha' 
				ELSE 'nao_trabalha' 
				END as 'cadastra_dia_trabalha',
				CASE 
				WHEN u.id = su.id_usuario AND (dse.data BETWEEN su.data_inicio AND su.data_final) AND su.status = 'valido' THEN tf.tipo_folgas 
				WHEN u.id IN (ts.id_usuario_tira, ts.id_usuario_solicitante) AND ((ts.data_tira = dse.data) OR (ts.data_solicitante = dse.data)) AND cts.libera_troca = 'sim' AND cts.pendente = 'nao' THEN 'usu_trabalha'
				ELSE 'nao_existe_nada' 
				END as 'troca_servico' 
				FROM usuario u
				INNER JOIN unidade_trabalho         ut  ON(u.id_unidade_trabalho = ut.id) 
				INNER JOIN ativacao_usuario         au  ON(u.id = au.id_usuario) 
				INNER JOIN setor                    s   ON(u.id_setor = s.id)
				LEFT JOIN setor_usuario             seu ON(u.id = seu.id_usuario)
				LEFT JOIN sub_setor                 subs ON(seu.id_sub_setor = subs.id)
				LEFT JOIN estado_trabalhado         et  ON(subs.id = et.id_sub_setor)
				LEFT JOIN horas                     he  ON(subs.id_hora_entrada = he.id)
				LEFT JOIN horas                     hs  ON(subs.id_hora_saida = hs.id)
				LEFT JOIN id_setor_dias_semanais    isds ON(subs.id = isds.id_sub_setor)
				LEFT JOIN dias_semanais             dias ON(isds.id_dias_semanais = dias.id)
				LEFT JOIN data_semanais             dse ON(et.id_data_semanais = dse.id)
				LEFT JOIN dias_semanais             ds  ON(dse.id_dias_semanais = ds.id) 
				LEFT JOIN status_usuario            su  ON(u.id = su.id_usuario)
				LEFT JOIN tipos_folgas              tf  ON(su.id_tipos_folgas = tf.id)
				LEFT JOIN troca_servico             ts  ON(u.id = ts.id_usuario_tira OR u.id = ts.id_usuario_solicitante) 
				LEFT JOIN comfirmar_troca_servico   cts ON(ts.id = cts.id_troca_servico)
				LEFT JOIN dia_trabalha              dt  ON(u.id = dt.id_usuario)
				WHERE u.id_unidade_trabalho = '".$unidade."'  
				AND u.id_setor = '".$setor."' 
				AND dse.data BETWEEN '".$dataInicio."' AND '".$dataFim."'
				AND au.estado_ativado = 'ativado' 
				GROUP BY u.apelido, dse.data, subs.nome 
				ORDER BY subs.id ASC, u.id ASC, dse.data ASC";
		
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
	
	//Lista com o sub setores para o index.php
	public function ListaComSubSetorIndex($dataInicio, $dataFim, $subSetor){
	
		$sql = "SELECT u.apelido, et.estado, dse.data, u.id, au.estado_ativado, ds.nome_ingles, ds.nome, he.horas_trabalhadas as hora_entrada, hs.horas_trabalhadas as hora_saida, subs.nome as nome_sub_setor, subs.nome as nome_sub_setor, 
				CASE
				WHEN u.id = su.id_usuario AND (dse.data BETWEEN su.data_inicio AND su.data_final) AND su.status = 'valido' THEN tf.cor 
				ELSE 'nao' 
				END as 'cores' ,
				 CASE
				WHEN u.id = dt.id_usuario AND (dse.data BETWEEN dt.data_inicio AND dt.data_final) AND dt.status = 'ativado' THEN 'dia_trabalha' 
				ELSE 'nao_trabalha' 
				END as 'cadastra_dia_trabalha',
				CASE 
				WHEN u.id = su.id_usuario AND (dse.data BETWEEN su.data_inicio AND su.data_final) AND su.status = 'valido' THEN tf.tipo_folgas 
				WHEN u.id IN (ts.id_usuario_tira, ts.id_usuario_solicitante) AND ((ts.data_tira = dse.data) OR (ts.data_solicitante = dse.data)) AND cts.libera_troca = 'sim' AND cts.pendente = 'nao' THEN 'usu_trabalha'
				ELSE 'nao_existe_nada' 
				END as 'troca_servico' 
				FROM usuario u
				INNER JOIN unidade_trabalho         ut  ON(u.id_unidade_trabalho = ut.id) 
				INNER JOIN ativacao_usuario         au  ON(u.id = au.id_usuario) 
				INNER JOIN setor                    s   ON(u.id_setor = s.id)
				LEFT JOIN setor_usuario             seu ON(u.id = seu.id_usuario)
				LEFT JOIN sub_setor                 subs ON(seu.id_sub_setor = subs.id)
				LEFT JOIN estado_trabalhado         et  ON(subs.id = et.id_sub_setor)
				LEFT JOIN horas                     he  ON(subs.id_hora_entrada = he.id)
				LEFT JOIN horas                     hs  ON(subs.id_hora_saida = hs.id)
				LEFT JOIN id_setor_dias_semanais    isds ON(subs.id = isds.id_sub_setor)
				LEFT JOIN dias_semanais             dias ON(isds.id_dias_semanais = dias.id)
				LEFT JOIN data_semanais             dse ON(et.id_data_semanais = dse.id)
				LEFT JOIN dias_semanais             ds  ON(dse.id_dias_semanais = ds.id) 
				LEFT JOIN status_usuario            su  ON(u.id = su.id_usuario)
				LEFT JOIN tipos_folgas              tf  ON(su.id_tipos_folgas = tf.id)
				LEFT JOIN troca_servico             ts  ON(u.id = ts.id_usuario_tira OR u.id = ts.id_usuario_solicitante) 
				LEFT JOIN comfirmar_troca_servico   cts ON(ts.id = cts.id_troca_servico)
				LEFT JOIN dia_trabalha              dt  ON(u.id = dt.id_usuario)
				WHERE subs.id IN ('".
				$subSetor
				."') 
				AND dse.data BETWEEN '".$dataInicio."' AND '".$dataFim."'
				AND au.estado_ativado = 'ativado' 
				GROUP BY u.apelido, dse.data, subs.nome 
				ORDER BY subs.id ASC, u.id ASC, dse.data ASC";
		
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
	
	/*/Contar quantos usuario para lista para o index.php
	public function ContaUsuarioIndex($unidade, $setor){
		
		$sql = "SELECT count(u.apelido) as conta
				FROM usuario u 
				INNER JOIN unidade_trabalho ut ON(u.id_unidade_trabalho = ut.id) 
				INNER JOIN ativacao_usuario au ON(u.id = au.id_usuario) 
				INNER JOIN setor s ON (u.id_setor = s.id) 
				WHERE ut.id = '".$unidade."'
				AND s.id = '".$setor."' 
				AND au.estado_ativado = 'ativado'";
		
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
	
	//Lista todos os nomes do usuario, trocaServico.php
	public function ListaApelido($idSetor, $idUsuLogado){
	
		$sql = "SELECT u.apelido, u.id FROM usuario u 
				INNER JOIN setor s ON(u.id_setor = s.id) 
				INNER JOIN ativacao_usuario au ON(u.id = au.id_usuario)
				WHERE s.id = '".$idSetor."' 
				AND u.id <> '".$idUsuLogado."'
				AND au.estado_ativado = 'ativado'";
		
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
	
	//Lista os uaurio para o chefe para 
	public function ListaUsuChefe(){
	
		$sql = "SELECT apelido, id FROM usuario";
		
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
	
	//Lista para castradaOutroSetor.php
	public function ListaParaInserirOutroSetor($idUnidade){
	
		$sql = "SELECT id, apelido FROM usuario WHERE id_unidade_trabalho = '".$idUnidade."'";
		
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
	
	//Verificar se o usuario tem alguma troca de serviço para 
	//fazer, para o LIberaTrocaServico.php
	public function ListaTrocaServico($idSession){
	
		$sql = "SELECT u.id, u.nome_usuario FROM usuario u 
				INNER JOIN troca_servico ts ON (u.id = ts.id_usuario_tira)
				INNER JOIN comfirmar_troca_servico cts ON(ts.id = cts.id_troca_servico) 
				WHERE u.id = '".$idSession."' 
				AND cts.libera_troca = '' 
				AND cts.libera_troca_quem_tira = '' 
				AND cts.status = 'nao_verificado'";
		
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
	
	//Lista o usuario pelo sub setor, se no index existe o setor selecionado
	/* 
	public function ListaPeloSubSetor($setor){
	
		$sql = "SELECT * FROM sub_setor ss INNER JOIN setor s ON(ss.id_setor = s.id) 
				WHERE s.nome_setor = '".$setor."'";
		
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
		
	}*/
	
	//Lista as troca para a tela do index.php 
	public function ListaTrocaServicoUsuario($idSession){
	
		$sql = "SELECT cts.id, u.nome_usuario, ts.data_tira FROM usuario u 
				INNER JOIN troca_servico ts ON (u.id = ts.id_usuario_solicitante)
				INNER JOIN comfirmar_troca_servico cts ON(ts.id = cts.id_troca_servico) 
				WHERE ts.id_usuario_tira = '".$idSession."' 
				AND cts.libera_troca = '' 
				AND cts.libera_troca_quem_tira = '' 
				AND cts.status = 'nao_verificado'";
		
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
	
	/*/Lista usuario pelo nome
	public function ListaParaIndex($dataListada){
	
		$sql = "SELECT dse.data, et.estado, u.apelido, he.horas_trabalhadas as hora_entrada, 
		hs.horas_trabalhadas as hora_saida, au.estado_ativado, ds.nome_ingles 
		FROM usuario u 
		INNER JOIN estado_trabalhado et ON (u.id = et.id_usuario) 
		INNER JOIN data_semanais dse ON (et.id_data_semanais = dse.id) 
		INNER JOIN dias_semanais ds ON (dse.id_dias_semanais = ds.id) 
		INNER JOIN ativacao_usuario au ON (au.id_usuario = u.id) 
		INNER JOIN setor s ON (u.id_setor = s.id) 
		INNER JOIN horas he ON (he.id = s.id_hora_entrada) 
		INNER JOIN horas hs ON (hs.id = s.id_hora_saida) 
		WHERE dse.data = '".$dataListada."' 
		AND au.estado_ativado = 'ativado'
        ";
		
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
		
	}*/
	
	//Lista todos os nomes do usuario par alterar
	public function ListaNomesParaAlteraSenha(){
	
		//Pegar o cpf para fazer o registro e alteraï¿½ï¿½o no banco de dados
		//Porque no email ele pegar o cpf e vou usar o mesmo CRUD
		$sql = "SELECT apelido, cpf FROM usuario WHERE status = 'ativado'";
		
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
	
	/* Verificar na tabela de função do usuario exercidade se ele ja tem o cadastro que esta sendo
	 * cadastrado nele
	 * */
	public function VerificaFuncaoUsuCad($codigoUsu){
	
		$sql = "SELECT * FROM usuario_funcao uf 
				INNER JOIN ativacao_usuario au ON(uf.codigo_ativacao_usuario = au.codigo_ativacao)
				WHERE uf.codigo_ativacao_usuario LIKE '".$codigoUsu."' 
				AND status LIKE 'ativado'";
		
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
	
	public function VerificaCodigoAtivacao($codigoUsu){
	
		$sql = "SELECT * FROM ativacao_usuario 
				WHERE codigo_ativacao LIKE '".$codigoUsu."' 
				AND estado_ativado LIKE 'ativado'";
		
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

	/*/Cadastra as paginas de acesso no usuario
	public function VerificaPaginaCadastrada($Idusu, $idPaginas){
		
		$sql = sprintf("SELECT id_tipo_acesso, id_usuarios FROM ids_usuarios_acesso_paginas 
		WHERE id_usuarios = '".$Idusu."' AND id_tipo_acesso = '".$idPaginas."'");
		
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
	
	//Lista usuario pelo nome para o listaUsuarios.php
	public function ListaUsuarioPeloNome($nomeUsuario){
		
		$sql = "SELECT DISTINCT(u.nome_usuario), u.id, u.apelido, u.email, s.nome_setor 
		FROM usuario u 
		INNER JOIN setor s ON(u.id_setor = s.id) 
		WHERE u.nome_usuario LIKE '".$nomeUsuario."%'";
		
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
	
	//Lista os usuario pelo id para a AlteraUsuario.php
	public function listaUsuarioParaAlterar($id){
	
		$sql = "SELECT nome_usuario, id, apelido, email, cpf 
				FROM usuario WHERE id = '".$id."'";
		
		$conn = new Conexao();
		$conn->openConnect();
	
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$resultado = mysqli_query($conn->getCon(), $sql);
		
		$conn->closeConnect ();
		
		$array = array();
	
		while ($row = mysqli_fetch_array($resultado)) {
			$array[]=$row;
		}
	
		return $array;
	
	}
	
	//altera a senha do usuario que perdeu a senha. A senha foi para o email dele 
	public function alterarSenhaEnviadaPorEmail($senha, $cpf) {
		
		$senha = md5 ( $senha );
		$comfirmar_senha = md5 ( $senha );
		
		$sql = "UPDATE usuario SET senha='" . $senha . "',  comfirmar_senha='" . $comfirmar_senha . "' WHERE cpf = '" . $cpf . "'";
		
		$conn = new Conexao ();
		$conn->openConnect ();
		
		mysqli_select_db ( $conn->getCon (), $conn->getBD());
		$resultado = mysqli_query ( $conn->getCon (), $sql );
		
		$conn->closeConnect ();
		
		return true;
		
	}
	
	//Retorna a lista como todos os email e cpf para recuperar a senha
	public function listaPeloEmailECpf($cpf, $email){
		
		$sql = "SELECT email, cpf, nome_usuario FROM usuario WHERE cpf = '".$cpf."' OR email LIKE '".$email."'";
		
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
	
	//Lista usuario para o listaUsuario.php, estadoUsuario.php
	public function listaUsuario(){
		
		$sql = "SELECT DISTINCT(u.nome_usuario), u.id, u.apelido, u.email, s.nome_setor 
				FROM usuario u 
				INNER JOIN setor s ON(u.id_setor = s.id)";
		
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
	
	//retorna a lista de usuario pela menas unidade de trabalho e setor para a passaPlantao.php
	public function listaUsuParaPlantao($idSetor, $idSession){
		
		$sql = "SELECT u.nome_usuario, u.id
		FROM usuario u 
		INNER JOIN setor s ON(u.id_setor = s.id)
        INNER JOIN unidade_trabalho ut ON(u.id_unidade_trabalho = ut.id)
        WHERE s.id = '".$idSetor."'
        AND u.id <> '".$idSession."'";
		
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
	
	//Lista usuario para liberar as paginas de acessos, e para cadastrar outra unidade de trabalho
	public function listaNomesUsuario(){
		
		$sql = "SELECT id, apelido	FROM usuario";
		
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
	
	//Alterar a senha do usuï¿½rio
	public function AlterarSenha($senha, $senha_comfirma, $id) {
		
		try {
			
			$sql = "UPDATE usuario SET senha = '".md5 ( $senha)."', comfirmar_senha = '".md5 
			( $senha_comfirma)."' WHERE id = '".$id."'";
			
			$conn = new Conexao ();
			$conn->openConnect ();
			
			$mydb = mysqli_select_db ( $conn->getCon (), $conn->getBD() );
			$resultado = mysqli_query ( $conn->getCon (), $sql );
			
			$conn->closeConnect ();
			
			return true;
			
		} catch ( PDOException $e ) {
			return $e->getMessage();
		}
		
	}
	
	//Verifica a senha no banco de dados para alterar a senha
	public function VerificaSenha($senhaAtual){
		
		$senha = md5 ( $senhaAtual);
		$sqlSenha = "SELECT senha FROM usuario WHERE senha LIKE '".$senha."'";
		
		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$resSenha = mysqli_query($conn->getCon(), $sqlSenha);
		
		$conn->closeConnect ();
		
		if (!empty(mysqli_fetch_assoc($resSenha))){
			return true;
		} else {
			return false;
		}
		
	}
	
	//Lista ultimo usuario cadastrado
	public function ListaUltimoUsuario($cpf){
		
		$sql = "SELECT id FROM usuario WHERE cpf LIKE '".$cpf."'";
		
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
	
	//Retorna a para o cadastro se o cpf, nome, login, telefone, $email
	public function ValidarDadosParaAlterar($nome, $apelido, $email, $idUsuario){
	
		$sqlNome    = "SELECT nome_usuario FROM usuario WHERE nome_usuario LIKE '".$nome."' AND id <> '".$idUsuario."'";
		$sqlApelido = "SELECT apelido      FROM usuario WHERE apelido      = '".$apelido."' AND id <> '".$idUsuario."'";
		$sqlEmail   = "SELECT email        FROM usuario WHERE email        = '".$email."'   AND id <> '".$idUsuario."'";
		
		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$resultNome    = mysqli_query($conn->getCon(),     $sqlNome); 
		$resultApelido = mysqli_query($conn->getCon(),    $sqlApelido); 
		$resultEmail   = mysqli_query($conn->getCon(),      $sqlEmail);
		
		$conn->closeConnect ();
		
		//Inicia os array e atribuir ao seus valores
		$arrayNome     = array();
		while ($row = mysqli_fetch_assoc($resultNome)) {
			$arrayNome[]=$row;
		}
		
		$arrayApelido  = array();
		while ($row = mysqli_fetch_assoc($resultApelido)) {
			$arrayApelido[]=$row;
		}
		
		$arrayEmail    = array();
		while ($row = mysqli_fetch_assoc($resultEmail)) {
			$arrayEmail[]=$row;
		}
		
		if (isset($arrayNome) || isset($arrayApelido) || 
				isset($arrayEmail)){
		
			$returnArray = array(
				"nome" => $arrayNome,
				"apelido"  => $arrayApelido,
				"email"  => $arrayEmail
			);
			
			return $returnArray;
			
		} else { 
			
			return true; 
			
			
		}
		
	}
	
	//Verificar se a unidade esta cadastrar no usuario
	public function VerificarUnidadeCadParaAlterar($idUnidade, $idUsuario, $idSetor){
	
		$sql = "SELECT id_unidade_trabalho, id, id_setor FROM usuario 
				WHERE id = '".$idUsuario."' 
				AND id_unidade_trabalho = '".$idUnidade."' 
				AND id_setor = '".$idSetor."'";
		
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
	
	
	//Retorna a para o cadastro se o cpf, nome, $email
	public function ValidarDados($cpf, $nome, $email, $apelido){
	
		$sqlCpf     = "SELECT cpf           FROM usuario WHERE cpf           =    '".$cpf."'";
		$sqlNome    = "SELECT nome_usuario  FROM usuario WHERE nome_usuario  LIKE '".$nome."'";
		$sqlEmail   = "SELECT email         FROM usuario WHERE email         =    '".$email."'";
		$sqlApelido = "SELECT apelido       FROM usuario WHERE apelido       =    '".$apelido."'";
		
		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		$resultNome    = mysqli_query($conn->getCon(), $sqlNome); 
		$resultEmail   = mysqli_query($conn->getCon(),  $sqlEmail); 
		$resultCpf     = mysqli_query($conn->getCon(),  $sqlCpf);
		$resultApelido = mysqli_query($conn->getCon(),  $sqlApelido);
		
		$conn->closeConnect ();
		
		//Inicia os array e atribuir ao seus valores
		$arrayNome     = array();
		while ($row = mysqli_fetch_assoc($resultNome)) {
			$arrayNome[]=$row;
		}
		
		$arrayApelido  = array();
		while ($row = mysqli_fetch_assoc($resultApelido)) {
			$arrayApelido[]=$row;
		}
		
		$arrayCpf      = array();
		while ($row = mysqli_fetch_assoc($resultCpf)) {
			$arrayCpf[]=$row;
		}
		
		$arrayEmail      = array();
		while ($row = mysqli_fetch_assoc($resultEmail)) {
			$arrayEmail[]=$row;
		}
		
		if (isset($arrayNome) || isset($arrayEmail) || 
				isset($arrayCpf) || isset($arrayApelido)){
		
			$returnArray = array(
			 "apelido"  => $arrayApelido,
				"nome"  => $arrayNome,
				"email" => $arrayEmail,
				"cpf"   => $arrayCpf
			);
			
			return $returnArray;
			
		} else { 
			
			return true; 
			
		}
		
	}
	
	//Colocar as paginas digitadas na session
	public function DadosSession($id){
		
		//Matar o valor especifo para não fica repetindo na session
		unset( $_SESSION['nome_paginas'] );
		
		$sql = "SELECT u.id, u.nome_usuario, s.nome_setor as setor, s.id as id_setor, 
				ta.nome as tipo_acesso_nome, ap.nome_paginas, ut.id as id_unidade
				FROM usuario u
				INNER JOIN setor s ON(u.id_setor = s.id)
				INNER JOIN unidade_trabalho ut ON(u.id_unidade_trabalho = ut.id)
                INNER JOIN tipo_acesso ta ON(u.id_tipo_acesso = ta.id)
                INNER JOIN id_tipo_acesso_paginas itap ON(ta.id = itap.id_tipo_acesso)
                INNER JOIN acesso_paginas ap ON(itap.id_acesso_paginas = ap.id)
				WHERE u.id = '".$id."'";
		
		$conn = new Conexao();
		$conn->openConnect();
		
		//Seleciona o banco de dados
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		
		$resultado = mysqli_query($conn->getCon(), $sql);
		
		$conn->closeConnect ();
		
		while ($linha = mysqli_fetch_assoc($resultado)) {
			
			$_SESSION['nome']             = $linha['nome_usuario'];
			$_SESSION['setor']            = $linha['setor'];
			$_SESSION['id_setor']         = $linha['id_setor'];
			$_SESSION['tipo_acesso_nome'] = $linha['tipo_acesso_nome'];
			$_SESSION['nome_paginas'][]   = $linha['nome_paginas'];
			
		}
		
	}
	
	public function autenticar($cpf, $senha, $idUnidade){
		
		$conn = new Conexao();
		$conn->openConnect();
		
		$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
		//Montar o sql
		$sql = "SELECT u.id, u.nivel_acesso, ut.nome as unidade_trabalho, iutu.id_unidade_trabalho FROM usuario u 
				INNER JOIN ativacao_usuario au ON(u.id = au.id_usuario)
				INNER JOIN id_unidade_trabalho_usuario iutu ON(u.id = iutu.id_usuario)
				INNER JOIN unidade_trabalho ut ON(iutu.id_unidade_trabalho = ut.id)
				WHERE u.cpf = '".$cpf."' 
				AND u.senha = '".md5($senha)."' 
				AND iutu.id_unidade_trabalho = '".$idUnidade."'
				AND au.estado_ativado = 'ativado'";
        
		//executar o sql
		$resultado = mysqli_query($conn->getCon(), $sql);
		$conn->closeConnect ();
		if (empty($resultado)){
			return false;
		} else {
			$linha = mysqli_fetch_assoc($resultado);
			//se achar algum resultado retorna verdadeiro
			while (mysqli_num_rows($resultado) > 0){
				
				$_SESSION['id'] = $linha['id'];
				$_SESSION['nivel_acesso']     = $linha['nivel_acesso'];
				$_SESSION['id_unidade']     = $linha['id_unidade_trabalho'];
				$_SESSION['unidade_trabalho'] = $linha['unidade_trabalho'];
				
				
				return true;
				
			} 
			
		}
		
	}
	
}
?>