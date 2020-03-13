<?php 
session_start();

include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/UnidadeHospitalarDAO.php';

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
<title>Cadastrada outra unidade de trabalho</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cadastrar_outra_unidade' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
	
?>

<div align="center">
	<div style="width: 80%">

<?php
/*Nessa pagina, pegara o ultimo cadastro do usuario
 * Para informar quais as paginas de acesso a ele
 * */
if (!empty($_POST)){
	
	if (empty($_POST['idUnidade']) || empty($_POST['idUsuario'])){
			?> <div class="alert alert-error"> <font size="3px" color="red"> Preenchar todos os campos </font> </div> <?php
	} else {
		
		$idUnidade = $_POST['idUnidade'];
		$idUsuario = $_POST['idUsuario'];
		
		//Verificar se o usuário já tem acesso ao sub setor
		$uniDAO = new UnidadeHospitalarDAO();
		$valida = $uniDAO->ValidaUnidadeCadastro($idUnidade, $idUsuario);
		
		if (empty($valida)){
				
			$uniDAO = new UnidadeHospitalarDAO();
			$result = $uniDAO->cadastraOutraUnidade($idUsuario, $idUnidade);
				
			if ($result){
				?>  <script type="text/javascript"> alert('Unidade de trabalho cadastrada com sucesso!'); window.location="index.php";  </script> <?php
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Ouve um erro </font> </div> <?php
			}
		
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Esse usuario ja foi cadastrado nesta unidade de trabalho</font> </div> <?php
		}
		
	}//Fecha o if que verificar se esta vazio

}//Fecha o if que verifica se o post foi executado
?>

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Cadastra uma unidade de trabalho</b></h1>
</div>

  <form action="" method="post">
	<br>
	<div class="w3-panel w3-blue"><h3>Informe a unidade de trabalho</h3></div>
	<select name="idUnidade" id="id_categoria" class="form-control">
		<option value="0" >Escolha a unidade</option>
			<?php 
				$uniDAO = new UnidadeHospitalarDAO();
				$arrayUnidade = $uniDAO->lista();
				foreach ($arrayUnidade as $uniDAO => $value){
			?>
				<option value="<?php echo $value['id']; ?>"><?php echo $value['nome']; ?></option>
				<?php } ?>
			</select><br>
	
	<div class="w3-panel w3-blue"><h3>Informe o usuario</h3></div>
	<select name="idUsuario" class="form-control">
		<option value="0" >Escolha o usuario</option>
			<?php 
				$usuDAO = new UsuarioDAO();
				$arrayUsu = $usuDAO->listaNomesUsuario();
				foreach ($arrayUsu as $usuDAO => $valor){
			?>
		<option value="<?php echo $valor['id']; ?>"><?php echo $valor['apelido']; ?></option>
			<?php } ?>
	</select><br>
	
	<input class="w3-btn w3-blue"  type="submit" value="  Cadastrar  " /><br><br>

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