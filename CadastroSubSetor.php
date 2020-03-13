<?php
session_start();
//Nessa chasse ela descrever quais são as regras para cada setor fazer o seu cadastro de horas

include_once 'Model/Modelo/Setor.php';
include_once 'Model/DAO/SetorDAO.php';
include_once 'Model/DAO/HorasDAO.php';
include_once 'Model/DAO/UnidadeHospitalarDAO.php';
include_once 'Model/DAO/DiasSemanaisDAO.php';
include_once 'Controller/ControllerCadNomeDiaSetor.php';

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
<title>Cadastrado de Sub Setor</title>
</head>
<body>
	
<?php 
if (!empty($_SESSION)){
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cadastro_sub_setor' : $as = 33;   break ;
		}
	}	
	if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
		//Pegar a unidade da session para lista os setores
		$idUnidade = $_SESSION['id_unidade'];
?>

<div align="center">
	<div style="width: 80%">
	
<?php
if (!empty($_POST)){
	
	if (empty($_POST['nome']) || empty($_POST['idHoraEntrada']) || 
		empty($_POST['idHoraSaida']) || empty($_POST['idSetor'])){
		?> <div class="alert alert-error"> <font size="3px" color="red"> Preencha todos os campos </font> </div> <?php
	} else {
		
		$nome = $_POST['nome'];
		//Colocar os dados em minusculo
		$nome = strtolower($nome);
		$idEntrada = $_POST['idHoraEntrada'];
		$idSaida = $_POST['idHoraSaida'];
		$idSetor = $_POST['idSetor'];
		
		$setDAO = new SetorDAO();
		$verificar = $setDAO->VerificarNomeSubSetor($nome, $idSetor);
				
		if (empty($verificar)){
			
			if (!empty($_POST['idDias'])){
				
				$diasArray = $_POST['idDias'];
				$estadoDias = "normal";
			
				if (count($diasArray) > 1){
					
					$status = "ativado";
					
					$cad = new SetorDAO();
					$result = $cad->cadastrarSubSetor($nome, $idEntrada, $idSaida, $status, $estadoDias, $idSetor);
					
					if($result){
							
						$con = new ControllerCadNomeDiaSetor();
						$res = $con->CadNomeDiaSetor($diasArray, $nome, $idSetor);
						
						if ($res){
							?>  <script type="text/javascript"> alert('Sub setor cadastrado com sucesso!'); window.location="index.php";  </script> <?php
						} else {
							?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastra as datas no setor, causa	possivel <?php echo $res; ?> </font> </div> <?php
						}
					
					} else {
						?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastra o setor, causa	possivel <?php echo $result; ?> </font> </div> <?php
					}
				
				} else {
					?> <div class="alert alert-error"> <font size="3px" color="red"> Insira pelo menos dois dias da da semana! </font> </div> <?php
				}
			
			} else {
			
				$estadoDias = "par_impar"; 
				$status = "ativado";
				
				$cad = new SetorDAO();
				$result = $cad->cadastrarSubSetor($nome, $idEntrada, $idSaida, $status, $estadoDias, $idSetor);
				
				if($result){
					?> <script type="text/javascript"> alert('Sub setor cadastrado com sucesso!'); window.location="index.php";  </script> <?php
				} else {
					?> <div class="alert alert-error"> <font size="3px" color="red"> Erro ao cadastrar o setor! </font> </div> <?php
				}
				
			}
	
		} else {
			?> <div class="alert alert-error"> <font size="3px" color="red"> Esse nome do sub setor ja foi cadastrado, para esse setor selecionado! </font> </div> <?php
		}

	}

}//Fecha o if que verifica se o post foi executado
?>

<div class="w3-panel w3-blue">
<a href="index.php" class="w3-left"><i class="fa fa-level-up"></i></a>
  <h1 class="w3-text-orange" style="text-shadow:1px 1px 0 #444">
  <b>Cadastro de sub setor</b></h1>
</div>

	<form action="" method="post" id="form" name="form">

<?php if (empty($_GET)){ ?>
						
<a href="CadastroSubSetor.php?acao=diaParImpar" class="w3-btn w3-blue"> Dias pares e impares </a>
<a href="CadastroSubSetor.php?acao=diaNormal"  class="w3-btn w3-blue"> Dias normais </a>

<?php } else if ($_GET['acao'] === "diaNormal"){ ?>

		<span>Informe o hospital do setor</span>
		<select name="idSetor" class="w3-input w3-border w3-light-grey" id="appendedInput">
			<option value="">Selecionar um setor</option>
			<?php 
				//Lista todas as unidade para lista na tela, quando selecionada
				$setDAO = new SetorDAO();
				$arraySetor = $setDAO->listaComUnidadeTrab($idUnidade);
				foreach ($arraySetor as $setDAO => $a){
			?>
				<option value="<?php echo $a['id']; ?>"><?php echo $a['nome_setor']; ?></option>
			<?php } ?>
		</select><br>

			<div class="control-group">
				<span>Informe o nome do sub setor</span>
				<input type="text" name="nome" required class="w3-input w3-border w3-light-grey" placeholder="Digite o nome do setor" maxlength="100" />
			</div>
			
			<div class="control-group">
			<br>
			<span>Informe os dias do setor semanais</span><br><br>
				<?php 
				$diaDAO = new DiasSemanaisDAO();
				$arrayDiaSema = $diaDAO->ListaParaCadSetor();
				foreach ($arrayDiaSema as $diaDAO => $list){
				?>
					<input type="checkbox" name="idDias[]" value="<?php echo $list['id']; ?>"><?php echo $list['nome']; ?>
				<?php } ?>
			</div><br>
			
			
			<div class="control-group">
			<span>Hora de entrada</span>
			<select name="idHoraEntrada" class="span2">
				<?php 
				$hoDAO = new HorasTrabalhadasDAO();
				$arrayHora = $hoDAO->lista();
				foreach ($arrayHora as $hoDAO => $listaHE){
				?>
					<option value="<?php echo $listaHE['id']; ?>"><?php echo $listaHE['horas_trabalhadas']; ?></option>
				<?php } ?>
			</select>
			<span>Hora de saida</span>
			<select name="idHoraSaida" class="span2">
			<?php 
				$horDAO = new HorasTrabalhadasDAO();
				$arrayHoras = $horDAO->lista();
				foreach ($arrayHoras as $horDAO => $listaHS){
				?>
					<option value="<?php echo $listaHS['id']; ?>"><?php echo $listaHS['horas_trabalhadas']; ?></option>
				<?php } ?>
			</select><br>
			</div><br>
			
			<br> <input class="w3-btn w3-blue" type="submit" value="  Cadastrar  " />

		<br><br><a href="CadastroSubSetor.php" class="w3-btn w3-blue"> Voltar na opcao </a>

		<?php } else if ($_GET['acao'] === "diaParImpar"){ ?>
		
		<span>Informe o hospital do setor</span>
		<select name="idSetor" class="w3-input w3-border w3-light-grey" id="appendedInput">
			<option value="">Selecionar um setor</option>
			<?php 
				//Lista todas as unidade para lista na tela, quando selecionada
				$setDAO = new SetorDAO();
				$arraySetor = $setDAO->listaComUnidadeTrab($idUnidade);
				foreach ($arraySetor as $setDAO => $b){
			?>
			<option value="<?php echo $b['id']; ?>"><?php echo $b['nome_setor']; ?></option>
			<?php } ?>
		</select><br>
		
			<div class="control-group">
				<span>Informe o nome do sub setor</span>
				<input type="text" name="nome" required placeholder="Digite o nome do setor" maxlength="100" class="w3-input w3-border w3-light-grey"/>
			</div><br>
			
			<div class="control-group">
			<span>Hora de entrada</span>
			<select name="idHoraEntrada" class="span2">
				<?php 
				$horaDAO = new HorasTrabalhadasDAO();
				$arryHora = $horaDAO->lista();
				foreach ($arryHora as $horaDAO => $listaHE){
				?>
					<option value="<?php echo $listaHE['id']; ?>"><?php echo $listaHE['horas_trabalhadas']; ?></option>
				<?php } ?>
			</select>
			<span>Hora de saida</span>
			<select name="idHoraSaida" class="span2">
			<?php 
				$horDAO = new HorasTrabalhadasDAO();
				$arrayHorasTrab = $horDAO->lista();
				foreach ($arrayHorasTrab as $horDAO => $listaHS){
				?>
					<option value="<?php echo $listaHS['id']; ?>"><?php echo $listaHS['horas_trabalhadas']; ?></option>
				<?php } ?>
			</select><br>
			</div><br>
					
			<br> <input class="w3-btn w3-blue" type="submit" value="  Cadastrar  " />
			
			<br><br><a href="CadastroSubSetor.php" class="w3-btn w3-blue"> Voltar na opcao </a>
		
		<?php } else { ?>
		
		<script type="text/javascript"> alert('Opcao incorreto, tente novamente!'); window.location="CadastroSubSetor.php";  </script>
		
		<?php } ?>
		
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