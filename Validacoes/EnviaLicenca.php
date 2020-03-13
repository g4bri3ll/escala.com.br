<?php
include_once 'ValidaCPFCNPJ.php';
include_once 'valida-email.php';
include_once 'Model/DAO/UsuarioDAO.php';
include_once 'PHPMailer/PHPMailerAutoload.php';
include_once 'gera-senhas.php';

function EnviarLicenca($cod) {
	
	$mail = new PHPMailer ();
	$mail->setLanguage ( 'pt' );
	
	// Utilizando o host do gmail para enviou de email
	$host = 'smtp.gmail.com';
	
	// Utilizando o hotmail para encio de email
	// $host = 'smtp.live.com';
	$username = 'gabrieldonascimentoborges@gmail.com';
	$password = 'gostomuitodevoce';
	// Altere a porta para 587 e a conexão para ssl
	// 465 porta do gmail e essa
	$port = 465;
	$secure = 'tls';
	
	$from = $username;
	$fromName = 'Gabriel do Nascimento';
	
	$mail = new PHPMailer ();
	$mail->isSMTP (); // Define que a mensagem será SMTP
	$mail->Host = $host; // Endereço do servidor SMTP
	$mail->SMTPAuth = true;
	$mail->Username = $username;
	$mail->Password = $password;
	$mail->Port = $port;
	$mail->SMTPSecure = $secure;
	
	$mail->From = $from;
	$mail->Fromname = $fromName;
	$mail->addReplyTo ( $from, $fromName );
	
	// quem vai receber o email, e o nome da pessoa que tiver recebendo o email
	$mail->addAddress ( "gabrieldonascimentoborges@gmail.com", "Gabriel do nascimento");
	
	$mail->isHTML ( true );
	$mail->CharSet = 'utf-8';
	$mail->WordWrap = 70;
	
	// Configurando a mensagem
	$mail->Subject = "Enviando email com php mailer";
	$mail->Body = "Enviando email com <b>PHPMailler</b> pela aula da <h2> web </h2> A lincen�a do sistema de portaria e :" . $cod;
	$mail->AltBody = "Enviando email pela aula da web";
	
	$send = $mail->Send ();
	
}
?>