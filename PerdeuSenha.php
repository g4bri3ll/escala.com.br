<?php

include_once 'Validacoes/ValidaCPFCNPJ.php';
include_once 'Validacoes/valida-email.php';
include_once 'Model/DAO/UsuarioDAO.php';
include_once 'PHPMailer/PHPMailerAutoload.php';
include_once 'Validacoes/gera-senhas.php';

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
<title>Altera Senha</title>
</head>
<body>
<script src="boot/js/bootstrap.js" type="text/javascript" ></script>
<script src="boot/js/bootstrap.min.js" type="text/javascript" ></script>
<script src="boot/js/npm.js" type="text/javascript" ></script>

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

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<div align="center"> 
<?php
if (!empty($_POST)) {
?> <br><br>	<?php
	if (empty($_POST['cpf']) || empty($_POST['email'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Campos vazio não permitido </font> </div> <?php
	} else {

		$cpf = $_POST ['cpf'];
		$email = $_POST ['email'];
		
		$validarCpf = new ValidaCPFCNPJ($cpf);
		$retEmail = verificar_email($email);
		
		if (!$validarCpf->valida()){
			?> <div class="alert alert-error"> <font size="3px" color="red"> CPF invalido, tente novamente! </font> </div> <?php
		} else if (!$retEmail) {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Email invalido, tente novamente! </font> </div> <?php
		} else {
		
			//Tira o ponto do cpf
			$cpf= preg_replace('#[^0-9]#', '', $cpf);
			
			$usuDAO = new UsuarioDAO ();
			$array = $usuDAO->listaPeloEmailECpf($cpf, $email);
			
			if(empty($array)){
				?> <div class="alert alert-error"> <font size="3px" color="red"> Email e CPF não achados, Coloque um CPF e Email ja cadastrado no sistema! </font> </div> <?php
			} else {
			
			//Colocar o valor nas variaveis
			foreach ($array as $usuDAO => $valores){
				$forNome = $valores['nome_usuario'];
				$forEmail = $valores['email'];
				$forCpf = $valores['cpf'];
			}
			
			if (empty($forNome)){
				?> <div class="alert alert-error"> <font size="3px" color="red"> Seu nome não esta cadastrado no sistema! </font> </div> <?php
			} else if (empty($forEmail)){
				?> <div class="alert alert-error"> <font size="3px" color="red"> Email não encontrado no sistema! </font> </div> <?php
			} else if (empty($forCpf)){
				?> <div class="alert alert-error"> <font size="3px" color="red"> CPF não encontrado no sistema! </font> </div> <?php
			} else {
			
				$mail = new PHPMailer();
				$mail->setLanguage('pt');

				//Utilizando o host do gmail para enviou de email
				$host             = 'smtp.gmail.com';

				//Utilizando o hotmail para encio de email
				//$host              = 'smtp.live.com';
				$username         = 'gabrieldonascimentoborges@gmail.com';
				$password         = 'gostomuitodevoce';
				//Altere a porta para 587 e a conexÃ£o para ssl
				//465 porta do gmail e essa
				$port               = 465;
				$secure           = 'tls';

				$from             = $username;
				$fromName ='Gabriel do Nascimento';

				$mail= new PHPMailer;
				$mail->isSMTP(); // Define que a mensagem serÃ¡ SMTP
				$mail->Host        = $host; // EndereÃ§o do servidor SMTP
				$mail->SMTPAuth    = true;
				$mail->Username    = $username;
				$mail->Password    = $password;
				$mail->Port        = $port;
				$mail->SMTPSecure  = $secure;

				$mail->From        = $from;
				$mail->Fromname    = $fromName;
				$mail->addReplyTo($from, $fromName);

				//quem vai receber o email, e o nome da pessoa que tiver recebendo o email
				$mail->addAddress($forEmail,  $forNome);

				$mail->isHTML(true);
				$mail->CharSet = 'utf-8';
				$mail->WordWrap = 70;

				//Configurando a mensagem
				$mail->Subject = "Enviando email com php mailer";
				//Gera uma nova senha
				$novaSenha = geraSenha(10, true, true, true);
				$mail->Body = "Enviando email com <b>PHPMailler</b> pela aula da <h2> web </h2> e a sua nova senha e :".$novaSenha;
				$mail->AltBody = "Enviando email pela aula da web";

				$send = $mail->Send();

				if($send){
					?> <div class="alert alert-error"> <font size="3px" color="red"> Nova senha enviado para o email: <?php echo $forEmail; ?> </font> </div> <?php
					$senha = $novaSenha;
					$cpf = $valores['cpf'];
					$usuDAO = new UsuarioDAO();
					$result = $usuDAO->alterarSenhaEnviadaPorEmail($senha, $cpf);
					if ($result){
						?> <script type="text/javascript"> alert('Em email foi enviado com a nova senha!'); window.location="index.php"; </script>	<?php
					} else {
						?> <script type="text/javascript"> alert('Ocorreu um erro ao alterar a senha!'); window.location="Login.php"; </script>	<?php
					}
					
				} else {
					?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao enviar : <?php echo $mail->ErrorInfo; ?>  </font> </div> <?php
				}

				}//Verificar se todos os dados vindo do array estão preenchidos, o cpf, nome e email
				
			}// Se o array de de cpf e email existe. Fecha o else 

		}//Fecha o else se o email e cpf for valido
		
	}//Fecha o else se esta verificando se os campos não estão vazios
	
}//Fecha o if que esta verificando se existe o $_POST
?>

			<h2>Recupere a sua senha aqui!</h2><br>
				<form action="" method="post">
				<div class="control-group">
					<span><img alt="" src="fotos/cpf.jpg"> </span>
					<input type="text" name="cpf" required placeholder="Digite seu cpf" id="cpf" class="input-xlarge" required="required" maxlength="14" size="8" onKeyPress="return digitos(event, this);" onKeyUp="Mascara('CPF',this,event);"/>
		        </div><br>
		        <div class="control-group">
					<span><img alt="" src="fotos/email.jpg"> </span>
					<input type="email" name="email" required placeholder="Informe seu Email Cadastrado" class="input-xxlarge" maxlength="36" required="required"/>
		        </div><br>
		        <input type="submit" value="Recuperar Senha" class="btn btn-info span3" />
		        <input type="reset" value="limpa" class="btn btn-info span2" /><br>
				</form>
			</div>
		</div>
	</div>
</nav>

<div align="center"> <a href="Login.php"  class="btn btn-info">Voltar ao Login</a> </div>

</body>
</html>