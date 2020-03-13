<?php
//Essa validaчуo de email so funcionar apartir do php 5 ou superior
function verificar_email($email){
	if(filter_var($email, FILTER_VALIDATE_EMAIL)){
		return $email;
	} else {
		return false;
	}
}
?>