<?php 
session_start();

include_once 'Model/Modelo/Usuario.php';
include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/SetorDAO.php';
include_once 'Validacoes/ValidarData.php';
include_once 'Model/DAO/AtivacaoUsuarioDAO.php';
include_once 'Model/DAO/UnidadeHospitalarDAO.php';
include_once 'Validacoes/ValidaCPFCNPJ.php';
include_once 'Controller/ControllerCadDataUsuario.php';
include_once 'Validacoes/gera-senhas.php';
include_once 'Model/DAO/TipoAcessoDAO.php';

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
<title>Cadastrado de Usuario</title>


<script type="text/javascript">
//--->Função para a formatação dos campos...<---
function Mascara(tipo, campo, teclaPress) {
	if (window.event)
	{
		var tecla = teclaPress.keyCode;
	} else {
		tecla = teclaPress.which;
	}
 
	var s = new String(campo.value);
	// Remove todos os caracteres à seguir: ( ) / - . e espaço, para tratar a string denovo.
	s = s.replace(/(\.|\(|\)|\/|\-| )+/g,'');
 
	tam = s.length + 1;
 
	if ( tecla != 9 && tecla != 8 ) {
		switch (tipo)
		{
		case 'CPF' :
			if (tam > 3 && tam < 7)
				campo.value = s.substr(0,3) + '.' + s.substr(3, tam);
			if (tam >= 7 && tam < 10)
				campo.value = s.substr(0,3) + '.' + s.substr(3,3) + '.' + s.substr(6,tam-6);
			if (tam >= 10 && tam < 12)
				campo.value = s.substr(0,3) + '.' + s.substr(3,3) + '.' + s.substr(6,3) + '-' + s.substr(9,tam-9);
		break;
 		}
	}
}

//--->Função para verificar se o valor digitado é número...<---
function digitos(event){
	if (window.event) {
		// IE
		key = event.keyCode;
	} else if ( event.which ) {
		// netscape
		key = event.which;
	}
	if ( key != 8 || key != 13 || key < 48 || key > 57 )
		return ( ( ( key > 47 ) && ( key < 58 ) ) || ( key == 8 ) || ( key == 13 ) );
	return true;
}
</script>


</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cadastro_usuario' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
	
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("jquery", "1.4.2");
</script>
		
<script type="text/javascript">
$(function(){
	$('#id_categoria').change(function(){
		if( $(this).val() ) {
			$('#id_sub_categoria').hide();
			$('.carregando').show();
			$.getJSON('carrega_setor.php?search=',{id_categoria: $(this).val(), ajax: 'true'}, function(j){
				var options = '<option value="#">Escolha Subcategoria</option>';	
				for (var i = 0; i < j.length; i++) {
					options += '<option value="' + j[i].id + '">' + j[i].nome_sub_categoria + '</option>';
				}	
				$('#id_sub_categoria').html(options).show();
				$('.carregando').hide();
			});
		} else {
			$('#id_sub_categoria').html('<option value="">– Escolha Subcategoria –</option>');
		}
	});
});
</script>

<div align="center">
	<div style="width: 80%">

<?php
if (!empty($_POST)){
	
	if (empty($_POST['nome']) || empty($_POST['cpf']) || empty($_POST['email']) || 
			empty($_POST['senha']) || empty($_POST['comfirmarSenha']) || empty($_POST['id_setor']) || 
			empty($_POST['apelido']) ||	empty($_POST['idUnidadeTrabalho'])){
		
			?><div class="w3-panel w3-red"><h3>Cuidado!</h3> <p>Campos vazio não permitido</p></div><?php
		
	} else {
		
		$nome = $_POST['nome'];
		//Colocar os dados em minusculo
		$nome = strtolower($nome);
		$apelido = $_POST['apelido'];
		//Colocar os dados em minusculo
		$apelido = strtolower($apelido);
		$cpf = $_POST['cpf'];
		$email = $_POST['email'];
		$senha = $_POST['senha'];
		$comfirmar_senha = $_POST['comfirmarSenha'];
		$idSetor = $_POST['id_setor'];
		$unidadeTrabalho = $_POST['idUnidadeTrabalho'];
		$idTipoAcesso = $_POST['idTipoAcesso'];
		
		$cpf = str_replace("." , "" , $cpf); // Primeiro tira os pontos
		$cpf = str_replace("-" , "" , $cpf); // Depois tira o traco
		
		//Verifica se o cpf digitado e valido
		$valCPF = new ValidaCPFCNPJ();
		$resultValidaCPF = $valCPF->validaCPF($cpf);
		
		if ($resultValidaCPF){
		
			if ($senha !== $comfirmar_senha){
				?> <div class="alert alert-error"> <font size="3px" color="red"> A "senha" deve ser a mesma senha de "comfirme sua senha"! </font> </div> <?php
			} else {
			
				$usuarioDAO = new UsuarioDAO();
				$resultado = $usuarioDAO->ValidarDados($cpf, $nome, $email, $apelido);
				
				if (!empty($resultado['nome'])){
					?> <div class="alert alert-error"> <font size="3px" color="red"> Usuario ja cadastrado com esse nome </font> </div> <?php
				} else if (!empty($resultado['cpf'])){
					?> <div class="alert alert-error"> <font size="3px" color="red"> Usuario ja cadastrado com esse cpf </font> </div> <?php
				} else if (!empty($resultado['apelido'])){
					?> <div class="alert alert-error"> <font size="3px" color="red"> Usuario ja cadastrado com esse apelido </font> </div> <?php
				} else if (!empty($resultado['email'])){
					?> <div class="alert alert-error"> <font size="3px" color="red"> Usuário ja cadastrado com essa email </font> </div> <?php
				}else {
						
					//Inicia o cadastro do usuario pela primeira vez
					$usuario = new Usuario();
					$usuario->nome = $nome;
					$usuario->cpf = $cpf;
					$usuario->email = $email;
					$usuario->senha = $senha;
					$usuario->comfirmar_senha = $comfirmar_senha;
					$usuario->idSetor = $idSetor;
					$usuario->idUnidadeTrabalho = $unidadeTrabalho;
					$usuario->apelido = $apelido;
					$usuario->idTipoAcesso = $idTipoAcesso;
					$usuario->status = 'ativado';
					
					$cad = new UsuarioDAO();
					$result = $cad->cadastrar($usuario);
					
					if ($result){
						
						//Retorna o ultimo usuario cadastrado para cadastra no estado_trabalhado
						$usuDAO = new UsuarioDAO();
						$idusu = $usuDAO->ListaUltimoUsuario($cpf);
							
						//Pegar o id do ultimo usuario e colocar na variavel
						foreach ($idusu as $usuDAO => $lista){
							$id = $lista['id'];
						}
						
						//Cadastra a unidade de trabalho na tabela id_unidade_trabalho_usuario
						$idUsuario = $id; 
						$idUnidade = $unidadeTrabalho;
						$uniDAO = new UnidadeHospitalarDAO();
						$result = $uniDAO->cadastraOutraUnidade($idUsuario, $idUnidade);
				
						
						//Gera uma nova senha
						$codAtivacao = geraSenha(5, true, true, true);
						$status = "desativado";
						
						//Cadastra o codigo do usuario, para ativar o usuario cadastrado
						$atvDAO = new AtivacaoUsuarioDAO();
						$resultCodAtivacao = $atvDAO->cadastrarAtivacao($codAtivacao, $id, $status);
						
						if ($resultCodAtivacao){
							?>  <script type="text/javascript"> alert('Usuario cadastrado com sucesso, solicita ao administrador do sistema para ativar sua conta!'); window.location="index.php";  </script> <?php
						} else {
							?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastrar o codigo de ativacao, causa possivel: <?php print_r($resultCodAtivacao); ?> </font> </div> <?php
						}
						
					} else {
						?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastra o usuario, causa possivel <?php print_r($result); ?> </font> </div> <?php
					}
					
				}
			
			}//Fecha o else que esta verificando se as senhas estão certas
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> O CPF digitado nao e valido! </font> </div> <?php
		}
		
	}//fecha o post que verifica o se o campo esta vazio
	
}//Fecha o if que verifica se o post foi executado
?>
		<br><br>
		<div class="w3-container w3-blue">
		    <h2>Cadastro de Usuario</h2>
		</div><br>
		<form action="" method="post">
		  
		    <p><label class="w3-text-brown w3-left"><b>Unidade de trabalho</b></label>
		    <select name="idUnidadeTrabalho" id="id_categoria" class="w3-input w3-border w3-light-grey">
				<option value="">Escolha a Categoria</option>
				<?php
				$sql = "SELECT * FROM unidade_trabalho WHERE status LIKE 'ativado'";
				$conn = new Conexao();		$conn->openConnect();
				$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
				$result = mysqli_query($conn->getCon(), $sql); 
				$conn->closeConnect ();			
				while($row = mysqli_fetch_assoc($result) ) {
					echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
				}
				?>
			</select></p>
			
			<p><label class="w3-text-brown w3-left"><b>Informe o setor</b></label>
		    <select name="id_setor" id="id_sub_categoria" class="w3-input w3-border w3-light-grey">
				<option value="">Informe o setor</option>
			</select></p>
			
		    <p><label class="w3-text-brown w3-left"><b>Informe o tipo de acesso do usuario</b></label>
			<select name="idTipoAcesso" class="w3-input w3-border w3-light-grey">
				<option value="">Escolha o tipo de acesso</option>
				<?php 
					$tipDAO = new TipoAcessoDAO();
					$arrayAcesso = $tipDAO->ListaParaUsuario();
					foreach ($arrayAcesso as $tipDAO => $value){
				?>
				<option value="<?php echo $value['id']; ?>"><?php echo $value['nome']; ?></option>
				<?php } ?>
			</select></p>
			
		    <p><label class="w3-text-brown w3-left"><b>Apelido</b></label>
		    <input type="text" name="apelido" required class="w3-input w3-border w3-light-grey" placeholder="Digite seu apelido" maxlength="36"/></p>
		    
		    <p><label class="w3-text-brown w3-left"><b>Nome</b></label>
		    <input type="text" name="nome" required class="w3-input w3-border w3-light-grey" placeholder="Digite seu primeiro nome" maxlength="36" /></p>
		
		    <p><label class="w3-text-brown w3-left"><b>CPF</b></label>
		    <input type="text" name="cpf" required placeholder="Digite seu cpf" id="cpf" maxlength="14" onKeyPress="return digitos(event, this);" onKeyUp="Mascara('CPF',this,event);" class="w3-input w3-border w3-light-grey"/></p>
		    
		    <p><label class="w3-text-brown w3-left"><b>Senha</b></label>
		    <input type="password" name="senha" required placeholder="Digite sua senha" maxlength="36" class="w3-input w3-border w3-light-grey"/></p>
		    
		    <p><label class="w3-text-brown w3-left"><b>Comfirmar senha</b></label>
		    <input type="password" name="comfirmarSenha" required placeholder="Comfirme sua senha" maxlength="36" class="w3-input w3-border w3-light-grey"/></p>
		   
		    <p><label class="w3-text-brown w3-left"><b>Email</b></label>
		    <input type="email" name="email" required placeholder="Digite o email" maxlength="100" class="w3-input w3-border w3-light-grey"/></p>
		    
		    <p><button class="w3-btn w3-left w3-blue" type="submit" id="enviar">Cadastro</button>
		    <a href="index.php" class="w3-right w3-button w3-blue">Cancelar Cadastro</a></p>
		  </form>
		<br><br><br>
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