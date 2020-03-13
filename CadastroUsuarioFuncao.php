<?php 
session_start();

include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/FuncaoExercidaUsuarioDAO.php';
include_once 'Model/Modelo/Usuario.php';

//Atualizar com o fusio horario do brasil
date_default_timezone_set('America/Bahia');

?>
<!doctype html>
<html lang="pt-BR">
<head>
<link rel="stylesheet" href="CSS/style.css" />
<link rel="stylesheet" href="boot/css/bootstrap.css" />
<link rel="stylesheet" href="boot/css/bootstrap.css.map" />
<link rel="stylesheet" href="boot/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Cadastrado de funcao no usuario</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cadastro_funcao_usuario' : $cfu = 28;   break ;
		}
	}	
	if (!empty($cfu) || $_SESSION['nivel_acesso'] === "1"){
	
?>

<div align="center">
	<div style="width: 80%">

<?php
if (!empty($_POST)){
	
	if (empty($_POST['cidade']) || empty($_POST['bairro']) || empty($_POST['complemento']) || 
		empty($_POST['idFuncaoExercida']) || empty($_POST['cep']) || empty($_POST['codigoUsu'])){
		
			?><div class="w3-panel w3-red"><h3>Cuidado!</h3> <p>Campos vazio não permitido</p></div><?php
		
	} else {
		
		$cidade = $_POST['cidade'];
		//Colocar os dados em minusculo
		$cidade = strtolower($cidade);
		$bairro = $_POST['bairro'];
		//Colocar os dados em minusculo
		$bairro = strtolower($bairro);
		$complemento = $_POST['complemento'];
		//Colocar os dados em minusculo
		$complemento = strtolower($complemento);
		$idFuncaoExercida = $_POST['idFuncaoExercida'];
		$opcaoVisualizarPainel = $_POST['opcaoVisualizarPainel'];
		$cep = $_POST['cep'];
		$codigoUsu = $_POST['codigoUsu'];
		$dataCad = date('Y-m-d');
			
		$usuarioDAO = new UsuarioDAO();
		$resultado = $usuarioDAO->VerificaFuncaoUsuCad($codigoUsu);
				
		if (!empty($resultado)){
			?> <div class="alert alert-error"> <font size="3px" color="red"> Usuario ja tem uma funcao cadastrada </font> </div> <?php
		} else {
			
			$usuarioDAO = new UsuarioDAO();
			$verCodigo = $usuarioDAO->VerificaCodigoAtivacao($codigoUsu);
					
			if (empty($verCodigo)){
				?> <div class="alert alert-error"> <font size="3px" color="red"> Esse codigo de ativacao nao existe </font> </div> <?php
			} else {
				
				//Inicia o cadastro do usuario pela primeira vez
				$usuario = new Usuario();
				$usuario->cidade = $cidade;
				$usuario->bairro = $bairro;
				$usuario->complemento = $complemento;
				$usuario->idFuncaoExercida = $idFuncaoExercida;
				$usuario->opcaoVisualizarPainel = $opcaoVisualizarPainel;
				$usuario->codigoUsu = $codigoUsu;
				$usuario->cep = $cep;
				$usuario->status = 'ativado';
				$usuario->dataCadastro = $dataCad;
						
				$cad = new UsuarioDAO();
				$result = $cad->CadastrarFuncaoUsuario($usuario);
						
				if ($result){
					?>  <script type="text/javascript"> alert('Funcao usuario cadastrado com sucesso!'); window.location="index.php";  </script> <?php
				} else {
					?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastrar, causa possivel: <?php print_r($resultData); ?> </font> </div> <?php
				}

			}
		
		}
			
	}//
	
}//Fecha o if que verifica se o post foi executado
?>
		
		<div class="w3-container w3-blue">
			<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
		    <h2>Cadastro de funcao no usuario</h2>
		</div>
		<form action="" method="post">
		  
		    <p><label class="w3-text-brown w3-left"><b>Informe o codigo do usuario</b></label>
		    <input type="text" name="codigoUsu" required class="w3-input w3-border w3-light-grey" placeholder="Digite o codigo do usuario" maxlength="36"/></p>
		    
		    <p><label class="w3-text-brown w3-left"><b>Cidade</b></label>
		    <input type="text" name="cidade" required class="w3-input w3-border w3-light-grey" placeholder="Digite a cidade" maxlength="36"/></p>
		    
		    <p><label class="w3-text-brown w3-left"><b>Bairro</b></label>
		    <input type="text" name="bairro" required class="w3-input w3-border w3-light-grey" placeholder="Digite o bairro" maxlength="36" /></p>
		
		    <p><label class="w3-text-brown w3-left"><b>Complemento</b></label>
		    <input type="text" name="complemento" required placeholder="Digite o complemento" maxlength="50" class="w3-input w3-border w3-light-grey"/></p>
		    
		    <p><label class="w3-text-brown w3-left"><b>CEP</b></label>
		    <input type="text" name="cep" required placeholder="Digite o cep" maxlength="9" class="w3-input w3-border w3-light-grey"/></p>
		    
		    <p><label class="w3-text-brown w3-left"><b>Informe a funcao exercida pelo usuario</b></label>
			<select name="idFuncaoExercida" class="w3-input w3-border w3-light-grey">
			<option value="">Informar uma funcao</option>
				<?php 
					$funExeDAO = new FuncaoExercidaUsuarioDAO();
					$array = $funExeDAO->lista();
					foreach ($array as $funExeDAO => $value){
				?>
				<option value="<?php echo $value['id']; ?>"><?php echo $value['nome']; ?></option>
				<?php } ?>
			</select></p>
			
		    <br>
		    <p><label class="w3-text-brown w3-left"><b>Usuario ira visualizar o painel de servico ->
		    Sim: <input type="radio" name="opcaoVisualizarPainel" value="sim"/>
		    Nao: <input type="radio" name="opcaoVisualizarPainel" value="nao"/>
		    </b></label></p><br><br>
		    
		    <p><button class="w3-btn w3-left w3-blue" type="submit" id="enviar">Cadastro</button></p>
		  </form>
		
	</div>
</div>

<?php
	} else {
		?>  <script type="text/javascript"> alert('Usuario não tem privilegio de acessar esta pagina!'); window.location="index.php";  </script> <?php
	}
} else {
	header("Location: index.php");
}
?>

</body>
</html>