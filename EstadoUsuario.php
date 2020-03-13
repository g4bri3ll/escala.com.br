<?php 
session_start();

include_once 'Model/DAO/TipoFolgaDAO.php';
include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/StatusUsuarioDAO.php';
include_once 'Model/Modelo/StatusUsuario.php';

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
<title>Mudar o status usuario</title>
</head>
<body>
<script src="boot/js/bootstrap.js" type="text/javascript" ></script>
<script src="boot/js/bootstrap.min.js" type="text/javascript" ></script>
<script src="boot/js/npm.js" type="text/javascript" ></script>

<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'estado_usuario' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
?>

<div align="center">
	<div style="width: 80%">

<?php
if (!empty($_POST)){
	
	//Pegar a data de hoje para verificar se a data foi digitada corretamente
	$dataAtual = date('Y-m-d');
		
		//Pegar os dados do post
		$acao       = $_GET['acao'];
		$idUsu      = $_POST['idUsu'];
		$dataInicio = $_POST['inicio'];
		$dataFim    = $_POST['fim'];
		
		if ($dataInicio > $dataAtual){
		
			//Verificar se a data de inicio e maior que a data final
			if ($dataInicio > $dataFim){
				?> <div class="alert alert-error"> <font size="3px" color="red"> A data 'inicio' nao pode ser maior que a data 'fim' </font> </div> <?php
				header("Location: EstadoUsuario.php?acao="+$acao);
			} else {

				//Verificar se ja esta cadastrado essa data no usuario
				$staUsuDAO = new StatusUsuarioDAO();
				$arrayVerificar = $staUsuDAO->VerificarCadFerias($idUsu, $dataInicio, $dataFim);
					
				$valida = "";
				foreach ($arrayVerificar as $staUsuDAO => $valor){
					$valida = $valor['valida_usuario'];
				}
				
				if (empty($valida)){
					
					$statusUsu = new StatusUsuario();
					$statusUsu->status      = "valido";
					$statusUsu->dataInicio  = $dataInicio;
					$statusUsu->dataFinal   = $dataFim;
					$statusUsu->idUsuario   = $idUsu;
					$statusUsu->idTipoFolga = $acao;
					
					$staUsuDAO = new StatusUsuarioDAO();
					$result = $staUsuDAO->cadastra($statusUsu);
					
					if ($result){
						?> <script type="text/javascript"> alert('Cadastrada com sucesso!'); window.location="EstadoUsuario.php";  </script> <?php
					} else {
						?> <div class="alert alert-error"> <font size="3px" color="red"> Ocorreu um erro: <?php print_r($result); ?> </font> </div> <?php
						header("Location: EstadoUsuario.php?acao="+$acao);
					}
						
				} else if ($valida === "possui_troca_servico"){
					?> <div class="alert alert-error"> <font size="3px" color="red"> Existe uma troca de serviço entre as data, verificar e exclua, caso queria cadastrar a folga do usuario </font> </div> <?php
					header("Location: EstadoUsuario.php?acao="+$acao);
				} else {
					?> <div class="alert alert-error"> <font size="3px" color="red"> Existe uma folga cadastrada para esse colaborador nesta data </font> </div> <?php
					header("Location: EstadoUsuario.php?acao="+$acao);
				}
				
			}
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> A data digitada nao pode ser menor que a data de hoje </font> </div> <?php
			header("Location: EstadoUsuario.php?acao="+$acao);
		} 
		
	 
}
?>

<?php 
//Invoca a classe que lista os usuarios 
$tipDAO = new TipoFolgaDAO();
$arrayTipo = $tipDAO->ListaFolga();

//Invoca a classe que lista os usuarios 
$usuDAO = new UsuarioDAO();
$array = $usuDAO->listaUsuario();
?>

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Modificar o estado do usuario</b></h1>
</div>

<form action="" method="post" >

<?php if (empty($_GET)){ ?>

<div class="control-group">

<?php foreach ( $arrayTipo as $usu => $lista ) { ?>
	<font class="w3-blue"><a class="w3-button" href="EstadoUsuario.php?acao=<?php echo $lista['id']; ?>"><?php echo $lista['tipo_folgas']; ?></a></font>
<?php } ?>

</div>	

<?php } else { ?>
  	
<a href="EstadoUsuario.php"> Voltar ao menu </a><br><br>
<label>Informe a nome do colaborador</label><br>
<div class="control-group">
	<span><img alt="" src="fotos/nome.jpg" width="3%"  height="3%"> </span>
	<select name="idUsu" class="span5">
		<?php 
			foreach ($array as $usuDAO => $list){
		?>
			<option value="<?php echo $list['id']?>"><?php echo $list['apelido']; ?></option>
		<?php } ?>
	</select><br>
</div><br>

<div class="control-group">
<span> Informe a data inicio </span>
<input type="date" name="inicio">

<span> Informe a data final </span>
<input type="date" name="fim"><br>
</div><br>

<input type="submit" value=" cadastrar " class="w3-btn w3-blue">

<?php } ?>

</form>

	</div>
</div>

<?php
	} else {
		?>  <script type="text/javascript"> alert('Usuario nao tem privilegio de acessar esta pagina!'); window.location="index.php";  </script> <?php
	}
} else {
	header("Location: index.php");
}
?>

</body>
</html>