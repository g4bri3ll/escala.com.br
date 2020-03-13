<?php 
session_start();

include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/Modelo/TrocaServico.php';
include_once 'Model/DAO/TrocaServicoDAO.php';
include_once 'Model/DAO/ComfirmarTrocaServicoDAO.php';
include_once 'Model/DAO/ChefeSetorDAO.php';

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
<title>Troca de servico</title>
</head>
<body>

<?php 
if (!empty($_SESSION)){
	
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'troca_servico' : $as = 15;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
		
//Pegar o setor do usuario logado
$idSetor = $_SESSION['id_setor'];
//Pego o id do usuario para não lista ele
$idUsuLogado = $_SESSION['id'];
?>

<div align="center">
	<div style="width: 80%">

<?php
if (!empty($_POST)){
	
	if (empty($_POST['idUsuTira']) || empty($_POST['dataEleTira']) || empty($_POST['motivo'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Campos vazio nao permitido </font> </div> <?php
	} else {
		
		//Pegar os dados do post
		$idUsuTira    = $_POST['idUsuTira'];
		$dataEleTira  = $_POST['dataEleTira'];
		$motivoTroca  = $_POST['motivo'];
		
		//Pegar a data de hoje para verificar se a data foi digitada corretamente
		$dataAtual = date('Y-m-d');
			
		//Pegar o id do usuario logado para cadastrada
		$idUsuSession = $_SESSION['id'];
		
		//Verifica se a data voce tira esta com dados ou nao
		if (!empty($_POST['dataVoceTira'])){
			$dataVoceTira   = $_POST['dataVoceTira'];
		} else {
			$dataVoceTira   = "0000-00-00";
		}
		
		if ($dataEleTira > $dataAtual || $dataVoceTira > $dataAtual){
					
			//Verificar se ja esta cadastrado essa data no usuario
			$troDAO = new TrocaServicoDAO();
			$arrayVerificar = $troDAO->VerificarCad($idUsuSession, $dataEleTira, $idUsuTira, $dataVoceTira);
			
			//valida a variavel para ver se ele tem algum dado nela
			$valida = ""; $nome = "";
			foreach ($arrayVerificar as $troDAO => $valor){
				$valida = $valor['valida_usuario'];
				$nome = $valor['apelido'];
			}

			if (empty($valida) || $valida === "nao_possui_pendencias"){
				
				$troca = new TrocaServico();
				$troca->dataSolicitante      = $dataVoceTira;
				$troca->dataTira             = $dataEleTira;
				$troca->idUsuarioTira        = $idUsuTira;
				$troca->idUsuarioSolicitante = $idUsuSession;
				$troca->motivoTroca          = $motivoTroca;
				
				$troDAO = new TrocaServicoDAO();
				$result = $troDAO->cadastra($troca);
				
				if ($result){
					
					//Pegar os dados para cadastrar
					$pedente = "sim";
					$idQuemSolicitou = $idUsuSession;
					$idQuemTira = $idUsuTira;
					$status = "nao_verificado";
					
					//Pega o id do dia do cadastro
					$troDAO = new TrocaServicoDAO();
					$ultimoId = $troDAO->RetornaUltimoId();
					
					foreach ($ultimoId as $troDAO => $value){
						$idTrocaServico = $value['id'];
					}
					
					$comDAO = new ComfirmarTrocaServicoDAO();
					$res = $comDAO->cadastra($idQuemTira, $idQuemSolicitou, $pedente, $idTrocaServico, $status);
					
					if ($res){
						?> <script type="text/javascript"> alert('Solicitacao registrada com sucesso!'); window.location="index.php";  </script> <?php
					} else {
						?> <div class="alert alert-error"> <font size="3px" color="red"> Ocorreu um erro: <?php print_r($result); ?> </font> </div> <?php
					}
				} else {
					?> <div class="alert alert-error"> <font size="3px" color="red"> Ocorreu um erro: <?php print_r($result); ?> </font> </div> <?php
				}
				
			} else if ($valida === "possui_folga") {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Existe uma folga para <?php echo $nome; ?> </font> </div> <?php
				header("Locations: TrocaServico.php");
				
			} else if ($valida === "trabalhando"){
				?> <div class="alert alert-error"> <font size="3px" color="red"> Nesse dia solicitado o usuario esta trabalhando, não tem como fazer a troca </font> </div> <?php
					header("Locations: TrocaServico.php");
					
			} else {
				?> <div class="alert alert-error"> <font size="3px" color="red"> Ja existe uma troca cadastrada para essa data </font> </div> <?php
				header("Locations: TrocaServico.php");
			}
			
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Nao pode solicita troca com datas passadas </font> </div> <?php
			header("Locations: TrocaServico.php?acao="+$_GET['acao']);
		}
	
	}
	
}
?>

<?php 
//Invoca a classe que lista os usuarios 
$usuDAO = new UsuarioDAO();
$array = $usuDAO->ListaApelido($idSetor, $idUsuLogado);
?>

<div class="w3-panel w3-blue">
	<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
	<h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
	<b>Solicita troca de servico</b></h1>
</div>

<?php 
//Verificar se o chefe ou supervisor ja tem cadastro no setor para autorizar a troca
$idSetorSession = $_SESSION['id_setor'];
$cheDAO = new ChefeSetorDAO();
$arrayChefeSetor = $cheDAO->VerificarChefeCad($idSetorSession);

if (!empty($arrayChefeSetor)){

if (empty($_GET)){ ?>

<span>Nao vou trabalhar para ele</span>
<a href="TrocaServico.php?acao=nao_trabalha" class="w3-btn w3-blue"> Solicitar um troca </a>

<span>Vou trabalhar para ele</span>
<a href="TrocaServico.php?acao=trabalha" class="w3-btn w3-blue"> Solicitar ambos a troca </a>

<?php } else { ?>

<form action="TrocaServico.php" method="post" >
  	
<?php 
	if ($_GET['acao'] === "nao_trabalha"){
?>
  		
<label>Informe o nome do funcionario que deseja fazer a substituicao de servico</label><br>
<div class="control-group">
	<span><img alt="" src="fotos/nome.jpg" width="3%"  height="3%"> </span>
	<select name="idUsuTira" class="w3-input w3-border w3-light-grey">
		<?php 
			foreach ($array as $usuDAO => $list){
		?>
			<option value="<?php echo $list['id']?>"><?php echo $list['apelido']; ?></option>
		<?php } ?>
	</select><br>
</div><br>

<div class="control-group">
<span> Informe o dia que ele tira </span>
<input type="date" name="dataEleTira">
</div><br>

<label class="">Motivo da troca do servico:</label>
<textarea name="motivo" class="w3-input w3-border w3-light-grey"></textarea><br>

<input type="submit" value=" cadastrar solicitacao " class="w3-btn w3-blue">
  		
<br><br><a href="TrocaServico.php">Cancelar Solicitacao</a>
  		
<?php } else if ($_GET['acao'] === "trabalha"){ ?>
  	
<label>Informe o nome do funcionario que deseja fazer a substituicao de servico</label><br>
<div class="control-group">
	<span><img alt="" src="fotos/nome.jpg" width="3%"  height="3%"> </span>
	<select name="idUsuTira" class="w3-input w3-border w3-light-grey">
		<?php 
			foreach ($array as $usuDAO => $list){
		?>
			<option value="<?php echo $list['id']?>"><?php echo $list['apelido']; ?></option>
		<?php } ?>
	</select><br>
</div><br>

<div class="control-group">
<span> Informe o dia que ele tira </span>
<input type="date" name="dataEleTira">

<span> Informe o dia que voce tira </span>
<input type="date" name="dataVoceTira"><br>
</div><br>

<label class="">Motivo da troca do servico:</label>
<textarea name="motivo" class="w3-input w3-border w3-light-grey"></textarea><br>

<input type="submit" value=" cadastrar solicitacao " class="w3-btn w3-blue">
		
<br><br><a href="TrocaServico.php">Cancelar Solicitacao</a>
		
<?php } ?>

</form>

<?php 
} 
	} //Fechar o if que verificar se o setor tem chefe cadastrado nele
	else {
?>
	<div class="alert alert-error"> <font size="5px" color="red"> 
		O setor nao tem nenhum chefe cadastrado para fazer a liberacao da troca do servico,
		Entra em contato com a TI, para fazer o cadastro de algum chefe no setor!
	</font> </div>
<?php } ?>

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