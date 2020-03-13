<?php 

/*A tabela status_estado_trabalhado e pra ser colocar os id do estado_trabalhado nela e informar se esta ativo
 * caso o funcionario entre de ferias, licença, troca de servico, afastamento, e outros, o status dessa 
 * tabela tem que troca e informar qual o status do trabalho dele, se ele esta ativo 'trabalhando', oude ferias
 * dispensado, para facilitar o sql para lista no index
 * */

session_start();

include_once 'Model/DAO/UsuarioDAO.php';
include_once 'Model/DAO/DiasSemanaisDAO.php';
include_once 'Validacoes/ValidarDiasSemanais.php';
include_once 'Model/DAO/SetorDAO.php';
include_once 'Model/DAO/EstadoTrabalhadoDAO.php';
include_once 'Validacoes/ValidarData.php';
include_once 'Model/DAO/DataSemanaisDAO.php';
include_once 'Controller/ControllerCadastrarDataSemanais.php';
include_once 'Controller/ControllerCadastrarDataUsuario.php';
include_once 'Model/DAO/UnidadeHospitalarDAO.php';
include_once 'Controller/ControllerIndex.php';
include_once 'Model/DAO/StatusUsuarioDAO.php';
include_once 'Model/DAO/TrocaServicoDAO.php';
include_once 'Model/DAO/RecardoDAO.php';
include_once 'Model/DAO/ComfirmarTrocaServicoDAO.php';
include_once 'Model/DAO/TipoFolgaDAO.php';
include_once 'Model/DAO/DiaTrabalhaDAO.php';

//Atualizar com o fusio horario do brasil
date_default_timezone_set('America/Bahia');

//Colocar todos os dados na session
if (!empty($_SESSION)){
	$id = $_SESSION['id'];
	$usuDAO = new UsuarioDAO();
	$usuDAO->DadosSession($id);

	//Verificar se na session tem alguma pagina de cadastro, lista ou outros para fazer o dropdwan, não pode apagar isso
	foreach ($_SESSION['nome_paginas'] as $key) {
		switch ($key) {
			case 'cadastro_usuario'             : $cu = 1;    break ;
			case 'cadastro_setor'               : $cs = 2;    break ;
			case 'cadastro_unidade_hospitalar'  : $cuh = 3;   break ;
			case 'cadastro_paginas_acesso'      : $cpa = 4;   break ;
			case 'cadastro_tipo_acesso'         : $cta = 5;   break ;
			case 'lista_usuario'                : $lu = 6;    break ;
			case 'lista_setor'                  : $ls = 7;    break ;
			case 'lista_unidade_hospitalar'     : $luh = 8;   break ;
			case 'lista_estado_usuario'         : $leu = 9;   break ;
			case 'lista_pagina_acesso'          : $lpa = 10;  break ;
			case 'mudar_unidade_trabalho'       : $mut = 11;  break ;
			case 'troca_senha_usuario'          : $tsu = 12;  break ;
			case 'mudar_estado_usuario'         : $meu = 13;  break ;
			case 'ativa_usuario'                : $au = 14;   break ;
			case 'altera_senha'                 : $as = 15;   break ;
			case 'altera_setor'                 : $ase = 16;  break ;
			case 'altera_unidade_hospitalar'    : $auh = 17;  break ;
			case 'altera_usuario'               : $ausu = 18; break ;
			case 'estado_usuario'               : $eu = 19;   break ;
			case 'troca_servico'                : $ts = 20;   break ;
			case 'lista_usuario_ativado'        : $lua = 21;  break ;
			case 'passa_plantao'                : $pp = 22;   break ;
			case 'libera_troca_servico'         : $lts = 23;  break ;
			case 'lista_troca_servico'          : $ltser = 24;break ;
			case 'cadastrar_outro_setor'        : $cos = 25;  break ;
			case 'cadastro_tipo_folgas'         : $ctf = 26;  break ;
			case 'recardo'                      : $re = 27;   break ;
			case 'cadastro_estado_chamado'      : $cec = 28;  break ;
			case 'cadastro_funcao_usuario'      : $cfu = 29;  break ;
			case 'cadastro_funcao_exercida'     : $cfe = 30;  break ;
			case 'funcao_chefe_setor'           : $fcs = 31;  break ;
			case 'chefe_setor'                  : $cst = 32;  break ;
			case 'cadastro_sub_setor'           : $css = 33;  break ;
			case 'lista_sub_setor'              : $lss = 34;  break ;
			case 'dia_trabalho'                 : $dt  = 35;  break ;
			case 'cancela_dia_trabalho'         : $cdt = 36;  break ;
			case 'cadastrar_outra_unidade'      : $cou = 37;  break ;
			
			
		}
		
	}
	
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="Content-Type: text/html; charset=UTF-8" />
<link rel="stylesheet" href="boot/css/bootstrap.css" />
<link rel="stylesheet" href="boot/css/bootstrap.css.map" />
<link rel="stylesheet" href="CSS/style.css" />
<link rel="stylesheet" href="boot/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<title>Pagina Inicial</title>

<style type="text/css">
select {
    width: 73%;
    padding: 8px 10px;
    border: none;
    border-radius: 4px;
    background-color: #f1f1f1;
}
</style>

</head>

<body>

<div align="center">
	<div style="width: 80%">
	
		<?php if (!empty($_SESSION)){ ?>
		<div class="w3-blue" style="width: 100%; height: 38px;">
			<div class="w3-left">
				<label class="w3-bar-item w3-mobile" style="padding-top: 6px; padding-left: 5px;">Hospital: <?php echo $_SESSION['unidade_trabalho'];?></label>
			</div>
			<div class="w3-right">
				<label class="w3-bar-item w3-mobile">Seja bem vindo <?php echo " ". $_SESSION['nome'];?></label>
				<a href="Sair.php" class="w3-bar-item w3-button"><i class="fa fa-power-off"></i></a>
			</div>
		</div>
		<?php }	?>
		
		<div style="width: 100%; height: 150px; text-align: center;">
			<img alt="" src="fotos/logo.png" class="img-rounded" style="max-width: 100%; height: 150px;">
		</div>
		
		<!-- Nav -->
		<div class="col-sm-12">
			<div class="w3-bar w3-blue">
	    		<a href="#" class="w3-bar-item w3-button w3-mobile w3-green"><i class="fa fa-home"></i></a>
		
						<?php if (empty($_SESSION)){?> 
						
						<!-- Trigger the modal with a button -->
						<a href="#" onclick="document.getElementById('id01').style.display='block'" class="w3-bar-item w3-button w3-mobile">Login</a>
						
						<?php } else {	?>
						
						<?php if (!empty($pp) || $_SESSION['nivel_acesso'] === "1"){
								?><a href="PassaPlantao.php" class="w3-bar-item w3-button w3-mobile"> Passagem de plantao</a><?php
							} ?>
						
						<?php if (!empty($re) || $_SESSION['nivel_acesso'] === "1"){
								?><a href="Recardo.php" class="w3-bar-item w3-button w3-mobile"> Recardo ao colaborador</a><?php
							} ?>

			 			<?php //Verificar se o usuario tem permissão para fazer algum cadastro no sistema 
			 			if (!empty($ctf) || !empty($eu) || !empty($css) || !empty($cec) || !empty($cst) || !empty($cfe) || !empty($fcs) || !empty($cfu) || !empty($cu) || !empty($cuh) || !empty($cpa) || !empty($cs) || !empty($cos) || $_SESSION['nivel_acesso'] === "1"){?>
						<div class="w3-dropdown-hover">
					        <button class="w3-button">Cadastros</button>
					        <div class="w3-dropdown-content w3-bar-block w3-card-4">
					        	
			 				<?php  if (!empty($cu) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastroUsuario.php">Cadastro de usuario</a><?php
							} ?>
								
							<?php if (!empty($cuh) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastroUnidadesHospitalar.php">Cadastro de unidade hospitalar</a><?php
							}	?>
							
							<?php if (!empty($cs) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastroSetor.php">Cadastro de setor</a><?php
							}	?>
							
							<?php if (!empty($cpa) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastraTipoAcesso.php">Cadastro os tipos de acesso</a><?php
							}	?>
								
							<?php if (!empty($cos) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastraOutroSetor.php">Cadastro de sub setor no usuario</a><?php
							}	?>
								
							<?php if (!empty($ctf) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastroTipoFolgas.php">Cadastra tipos de folgas</a><?php
							}	?>
								
								<?php if (!empty($eu) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="EstadoUsuario.php">Cadastrar o tipo de folga do usuario</a><?php
							} 	?>
							
								<?php if (!empty($cec) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastrarEstadoChamado.php">Cadastra o estado do chamado</a><?php
							} 	?>
							
								<?php if (!empty($cfu) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastroUsuarioFuncao.php">Cadastrar funcao no usuario</a><?php
							} 	?>
							
								<?php if (!empty($cfe) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastrarFuncaoExercida.php">Cadastrar funcao exercida pelo usuario</a><?php
							} 	?>
							
								<?php if (!empty($fcs) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastraFuncaoChefeSetor.php">Cadastrar funcao chefe setor</a><?php
							} 	?>
						
								<?php if (!empty($cst) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastraChefeSetor.php">Cadastrar chefe no setor</a><?php
							} 	?>
						
								<?php if (!empty($css) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastroSubSetor.php">Cadastrar sub setor</a><?php
							} 	?>
						
								<?php if (!empty($dt) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastraDiaTrabalho.php">Cadastra dia trabalho no usuario</a><?php
							} 	?>
									
								<?php if (!empty($cou) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CadastraOutraUnidade.php">Cadastra outra unidade</a><?php
							} 	?>

							</div>
						</div>
						<?php } if (!empty($leu) || !empty($ltser) || !empty($lss) || !empty($lpa) || !empty($lu) || !empty($ls) || !empty($luh) || !empty($lua) || $_SESSION['nivel_acesso'] === "1") {?>
						<div class="w3-dropdown-hover">
					    	<button class="w3-button">Listas</button>
					    	<div class="w3-dropdown-content w3-bar-block w3-card-4">
					    	
							<?php if (!empty($leu) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="ListaEstadoUsuarios.php">Lista o estado dos usuarios</a><?php
							} ?>
							
							<?php if (!empty($ltser) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="ListaTrocaServico.php">Lista troca de servico</a><?php
							} ?>

							<?php if (!empty($lpa) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="ListaTipoAcesso.php">Lista de paginas de acessos</a><?php
							} ?>
							
							<?php if (!empty($lu) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="ListaUsuarios.php">Lista usuarios</a><?php
							} ?>
							
							<?php if (!empty($ls) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="ListaSetor.php">Lista setor</a><?php
							} ?>
							
							<?php if (!empty($luh) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="ListaUnidadesHospitalar.php">Lista unidades hospitalar</a><?php
							} ?>
							
							<?php if (!empty($lua) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="ListaUsuariosAtivado.php">Lista Usuarios Ativado</a><?php
							} ?>
							
							<?php if (!empty($lss) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="ListaSubSetor.php">Lista Sub Setor</a><?php
							} ?>
							
							
							</div>
						</div>
						<?php } if (!empty($ase) || !empty($as) || !empty($auh) || !empty($ausu) || $_SESSION['nivel_acesso'] === "1"){?>
						<div class="w3-dropdown-hover">
					        <button class="w3-button">Altera</button>
					    	<div class="w3-dropdown-content w3-bar-block w3-card-4">
					    	
					    	<?php if (!empty($ase) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="EnviaAlteraSetor.php">Altera o setor</a><?php
							} 	?>
					    	
							<?php if (!empty($as) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="AlteraSenha.php">Altera Senha</a><?php
							} 	?>
							
							<?php if (!empty($auh) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="EnviaAlteraUnidadesHospitalar.php">Altera a unidade hospitalar</a><?php
							} 	?>
							
							<?php if (!empty($ausu) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="EnviaAlteraUsuario.php">Altera usuario</a><?php
							} 	?>

							</div>
						</div>
						
						<?php } if ($_SESSION['nivel_acesso'] === "1"){?>
						<div class="w3-dropdown-hover">
					        <button class="w3-button">Lista de Cancelamentos</button>
					    	<div class="w3-dropdown-content w3-bar-block w3-card-4">
					    	
					    	<?php if ($_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="#">Usuarios</a><?php
							} 	?>
							
							<?php if ($_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="#">Trocas de servicos</a><?php
							} 	?>
							
							
							</div>
						</div>
						
						<?php } if (!empty($mut) || !empty($ts) || !empty($eu) || !empty($au) || $_SESSION['nivel_acesso'] === "1"){?>
						<div class="w3-dropdown-hover">
					        <button class="w3-button">Solicitacoes</button>
					    	<div class="w3-dropdown-content w3-bar-block w3-card-4">
					    	
					    	<?php if (!empty($mut) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="MudarUnidadeTrabalhoUsu.php">Mudar a unidade de trabalho usuario</a><?php
							} 	?>
							
							<?php if (!empty($ts) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="TrocaServico.php">Solicitar troca de servico</a><?php
							} 	?>
							
							<?php if (!empty($au) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="AtivaUsuario.php">Ativacao de usuario</a><?php
							} 	?>

							<?php if (!empty($lts) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="LiberaTrocaServico.php">Libera troca servico</a><?php
							} 	?>
							
							<?php if (!empty($cdt) || $_SESSION['nivel_acesso'] === "1"){
								?><a class="w3-bar-item w3-button" href="CancelaDiaTrabalho.php">Cancela dia trabalho</a><?php
							} 	?>
							
							
							</div>
						</div>
						
						<?php } } ?>
						
						<a href="help.php" class="w3-bar-item w3-button w3-mobile">Help</a>
						
				</div>
		
			<!-- Modal -->
			<div class="w3-container">
			  <div id="id01" class="w3-modal">
			    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">
			
			      <div class="w3-center"><br>
			        <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
			        <img src="fotos/usr.png" alt="Avatar" style="width:30%" class="w3-circle w3-margin-top">
			      </div>
			
			      <form action="Login.php" method="post" class="w3-container">
			        <div class="w3-section">
			          <input class="w3-input w3-border w3-margin-bottom" type="text" name="cpf" maxlength="15" required placeholder="Informe seu cpf">
			          <input class="w3-input w3-border" type="password" required name="senha" maxlength="36" placeholder="Informe sua senha">
			          <label><b>Unidade trabalho</b></label><br>
			          <select name="idUnidade" class="w3-input w3-border w3-light-grey" id="appendedInput">
						<option value="">Selecionar um setor</option>
						<?php 
						//Lista todas as unidade para lista na tela, quando selecionada
						$uniDAO = new UnidadeHospitalarDAO();
						$arrayUnidade = $uniDAO->lista();
						foreach ($arrayUnidade as $uniDAO => $u){
						?>
							<option value="<?php echo $u['id']; ?>"><?php echo $u['nome']; ?></option>
						<?php } ?>
					  </select><br>
			          <button class="w3-btn w3-border w3-block w3-section w3-padding w3-blue" type="submit">Login</button>
			        </div>
			      </form>
			
			      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
			        <button onclick="document.getElementById('id01').style.display='none'" type="button" class="w3-btn w3-blue w3-border-black w3-round-large w3-right">Cancel</button>
			      </div>
			
			    </div>
			  </div>
			</div>
		</div><br><br>
		
		
<!-- Tem que fazer aqui uma validação para que o usuario que solicita a troca
de serviço o outro funcionar possar acessar essa pagina para comfirmarção
da troca do servico -->
<?php
//Faz a verificação do usuario logado para ver se ele tem alguma troca de serviço solicitado
if (!empty($_SESSION['id'])){
	
	$idSession = $_SESSION['id'];
	
	$usuDAO = new UsuarioDAO();
	$arrayServico = $usuDAO->ListaTrocaServicoUsuario($idSession);
	
	if (!empty($arrayServico)){
		?><a href="ComfirmarTrocaServico.php" class="w3-btn w3-red w3-input">Comfirmar a solicitacao de troca de servico</a><br><br><?php
	}
	
	//Verificar se tem algum recardo para o usuario logado e o dia
	$recDAO = new RecardoDAO();
	$arrayRecardo = $recDAO->listaRercadoPeloUsuario($idSession);
	$id = 0;	
	foreach ($arrayRecardo as $recDAO => $valor){
		$id = $valor['id'];
	}
	if (!empty($arrayRecardo)){
		?><a href="ListaRecardoUsuario.php?recardo=<?php echo $id ?>" class="w3-btn w3-red w3-input">Le recardo enviado</a><br><br><?php
	}
	
	//Colocar aqui as pedencias do chefe do setor, para a liberação de servico
	$idUsuario = $_SESSION['id'];
	$idSetor = $_SESSION['id_setor'];
	$data = date('Y-m-d');
	
	$comDAO = new ComfirmarTrocaServicoDAO();
	$autorizarTrocaServico = $comDAO->AutorizarTrocaServico($idUsuario, $idSetor, $data);
	
	if (!empty($autorizarTrocaServico)){
		?><a href="AutorizaTrocaServico.php" class="w3-btn w3-red w3-input">Existe troca pedente de servico para autorizar!</a><br><br><?php
	}
	
}
?>
			
<?php
//Faz a verificação para ver, se o usuario logado tem permissão para ver os dias de 
//trabalhos dos funcionarios
if (!empty($_SESSION['nivel_acesso'])){ 
	if ($_SESSION['nivel_acesso'] === "1") { 
		$idUnidadeTrabalho = $_SESSION['id_unidade'];
?> 
		<div class="col-sm-12">
			<!-- Lista de unidade -->
			<form method="post" action="">
			<div class="row">
				<div class="col-sm-5" align="center">
					<select name="idUnidade" id="id_categoria" class="form-control">
						<option value="0" >Escolha a unidade de trabalho</option>
						<?php
							$sql = "SELECT s.id, s.nome_setor FROM unidade_trabalho ut 
									INNER JOIN setor s ON(ut.id = s.id_unidade_trabalho)
									WHERE ut.id = '".$idUnidadeTrabalho."' 
									AND ut.status = 'ativado' 
									AND s.status = 'ativado'";
							
							$conn = new Conexao();		$conn->openConnect();
							
							$mydb = mysqli_select_db($conn->getCon(), $conn->getBD());
							$result = mysqli_query($conn->getCon(), $sql); 
							
							$conn->closeConnect ();
							
							while($row = mysqli_fetch_assoc($result) ) {
								echo '<option value="'.$row['id'].'">'.$row['nome_setor'].'</option>';
							}
						?>
					</select>
				</div>
				<!-- Lista de setor -->
				<div class="col-sm-5" align="center">
					<select name="idSetor" id="id_sub_categoria" class="form-control">
						<option value="0" >Escolha o setor</option>
					</select>
				</div>
				<div class="col-sm-2" align="center">
					<input type="submit" value="Buscar" class="w3-btn w3-blue">
				</div>		
			</div>
			</form>
		</div>
		
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
			$('#id_sub_categoria').html('<option value="">– Escolha o setor –</option>');
		}
	});
});
</script>

<div class="col-sm-12">

<?php
//Verificar se tem no status do usuario alguma data passada que se encontra valido
$connt = new ControllerIndex();
$connt->ControllarStatusUsuario();

//Verificar o dia trabalhado se esta com a data passado e o status ativado
$cont = new ControllerIndex();
$cont->ControllarDiaTrabalho();

//Cadastrar as data no banco de dados de quinze dias a frente
$contData = new ControllerCadastrarDataSemanais();
$contData->CadastrarDiasNaData();

//Cadastrar as datas nos usuarios e não mexer mais
$ControllerCadDataUsu = new ControllerCadastrarDataUsuario();
$ControllerCadDataUsu->CadastrarDiasNosUsuarios();

if (!empty($_POST)){
	
	if ($_POST['idUnidade'] == 0 || $_POST['idSetor'] == 0){?><font size="8" color="black">Selecao errada!</font> <?php } else {
	
	//Pegar a hora e a data atual do computador
	$horaAtual = date('H:i');
	$dataHoje = date('Y-m-d');
	
	//Aumentar um dia, do dia do computador
	$dataInicio = date('Y-m-d', strtotime("-3 days",strtotime($dataHoje))); 
	$dataFim = date('Y-m-d', strtotime("+3 days",strtotime($dataHoje)));
	
	//Lista as datas da semana logo de inicio, separadamente
	$datDAO = new DataSemanaisDAO();
	$arrayDatas = $datDAO->ListaDiasSemanais($dataInicio, $dataFim);
	
	//Pegar a unidade e setor escolhido para lista
	$unidade = $_POST['idUnidade'];
	$setor = $_POST['idSetor'];

	
	/* Chama a unidade, setor, usuarios para lista
	 * Mostra na tela o status do usuario para lista na tabela
	 * E verificar o setor e a unidade
	 * Aqui esta fazendo a execução de tres query
	 * --------------------------------------------------------------------------
	 * */
	
	if (!empty($_POST['nomeSubSetor']) || !empty($_GET['setor'])){
		
		$subSetor = $_POST['nomeSubSetor'];
		
		//Lista os dados do usuario na tela, pelo setor e unidade de trabalho e datas
		$usuDAO = new UsuarioDAO();
		$arrayIndex = $usuDAO->ListaComSubSetorIndex($dataInicio, $dataFim, $subSetor);
		
	} else {
		
		//Lista os dados do usuario na tela, pelo setor e unidade de trabalho e datas
		$usuDAO = new UsuarioDAO();
		$arrayIndex = $usuDAO->ListaIndex($dataInicio, $dataFim, $unidade, $setor);
		
	}
	
	//Pegar a o setor para lista o sub setor
	$subSetDAO = new SetorDAO();
	$arraySubSetor = $subSetDAO->ListaSubSetor($setor);
	
	if (!empty($arrayIndex)){
		
?>
<br><br>
<!-- Tabela sobre as folgas -->
<table border="2">
<tr align="center">
<h3>Status da folga do usuario</h3>
</tr>
<tr>
<?php 
$tipDAO = new TipoFolgaDAO();
$arrayTipoFolga = $tipDAO->ListaFolga();
foreach ($arrayTipoFolga as $tipDAO => $val){
?>
<td style="background-color: <?php echo $val['cor']; ?>"><?php echo $val['tipo_folgas']; ?></td>
<?php } ?>
</tr>
</table>

<!-- Tabela sobre o servico -->
<table border="2">
<tr><h3>Legenda do painel de servico</h3></tr>
<tr>
<th>Dia que trabalho</th>
<th>Antes do horario trabalho</th>
<th>Plantão encerrado</th>
<th>trabalhando</th>
<th>Servico troca</th>
<th>folga</th>
</tr>
<tr>
<td style="background: #66CDAA;">Ciano</td>
<td style="background: #F0E68C;">Amarelo</td>
<td style="background: #DCDCDC;">Cinza</td>
<td style="background: #B22222;">Vermelho</td>
<td style="background: #8B4513;">Marron</td>
<td style="background: #FF8C00;">Laranja</td>
</tr>
</table><br><br>

		<h3>Selecionar o sub setor</h3>
		<form action="index.php?setor=escolha" method="post">
			<div class="subSetor">
				<?php 
				$fa = 1; 
				for ($i = 0;$i < count($arraySubSetor);$i++){
				?>
					<b class="listSubSetor"><input type="checkbox" name="nomeSubSetor[]" value="<?php echo $arraySubSetor[$i]['id']; ?>">
					<?php echo " -> " . $arraySubSetor[$i]['nome_sub_setor']; ?></b>
				<?php if ($i === $fa){ ?>	
					<br>
				<?php 
						$fa = $fa+2;
					} 
				} 
				?>
				<br> <b class="submitSubSetor"> <input type="submit" value="Buscar sub setor" class="btn w3-blue"> </b>
			</div>
		</form><br><br>
		
    <table class="w3-table w3-striped w3-border">
    <tr>
		<?php
		$corData = "";
		foreach ($arrayDatas as $datDAO => $lis){
			if ($lis['data'] === date('Y-m-d')){
				$corData = "#4169E1";
			} else { $corData = ""; }
			?>
			<td bgcolor="<?php echo $corData; ?>">
				<label class=""><?php echo $lis['nome']; ?></label>
				<label class=""><?php echo date('d-m-Y', strtotime($lis['data'])); ?></label>
			</td> 
		<?php } ?>
    </tr>
   
    <?php
    $contador = count($arrayDatas);
    $b = 0; 
    for ($a = 0; $a < count($arrayIndex);$a++){
    	if ($a === $contador){ 
    		$b = $b + count($arrayDatas); 
    		$contador = $contador + count($arrayDatas);
    	}
    ?>
    
    <?php if (($a === $b)) { ?>
    	<tr>
    <?php } ?>
    	
    	<?php if ($a < $contador){ ?>
    	
    		<?php
    		// colocar aqui as cores da legenda de acordo com o seu dia ou folga de trabalho
    		//Iniciar a cor como zero
    		$cor = "";
    		//Aqui mostra se o usuario tem alguma troca ou folga para mostrar
	    	if ($arrayIndex[$a]['troca_servico'] !== "nao_existe_nada"){
    			if ($arrayIndex[$a]['troca_servico'] === "usu_trabalha"){
    				//Marron
    				$cor = "#8B4513";
    			} else {
    				$cor = $arrayIndex[$a]['cores'];
    			} 
    		}
    		
    		//Verificar se o usuario esta cadastro na tabela de dia_trabalho 
    		else if ($arrayIndex[$a]['cadastra_dia_trabalha'] === "dia_trabalha"){
    			
    			//folga antes do horario de trabalho
    			if (date('H:i') <= $arrayIndex[$a]['hora_entrada']){
    				//Amarelo
    				$cor = "#F0E68C";
    			}
    			//Folga depois do trabalho
    			else if(date('H:i') >= $arrayIndex[$a]['hora_saida']){
    				//cinza
    				$cor = "#DCDCDC";
    			}
    			//Esta trabalhando
    			else {
    				//Vermelho
    				$cor = "#B22222";
    			} 
    			
    		}
    		
    		//Verificar se esta no hora de trabalhar 
    		else if (($arrayIndex[$a]['data'] === date('Y-m-d')) && ($arrayIndex[$a]['estado'] === "trabalha")) 
    		{ 
    			//folga antes do horario de trabalho
    			if (date('H:i') <= $arrayIndex[$a]['hora_entrada']){
    				//Amarelo
    				$cor = "#F0E68C";
    			}
    			//Folga depois do trabalho
    			else if(date('H:i') >= $arrayIndex[$a]['hora_saida']){
    				//cinza
    				$cor = "#DCDCDC";
    			}
    			//Esta trabalhando
    			else {
    				//Vermelho
    				$cor = "#B22222";
    			} 
    			
    		}
    		//Estar trabalhando neste dia
    		else if($arrayIndex[$a]['estado'] === "trabalha"){
    			//Ciano
    			$cor = "#66CDAA";
    		}
    		//Esta de folga
    		else if ($arrayIndex[$a]['estado'] === "folga"){
    			//Laranja
    			$cor = "#FF8C00";
    		} 
    		
    		?>
    			<td style="background: <?php echo $cor;?>;" align="center">
	    	   	<hr>
		       		<?php echo $arrayIndex[$a]['apelido']; ?>
		       		<?php echo $arrayIndex[$a]['nome_sub_setor']; ?>
				</td>
				
		<?php } ?>

    <?php if ($a < $b ) { ?>
    	</tr>
    <?php } ?>
	
	<?php } ?>
	 
  </table>
  
<?php } else { ?>
<font size="8" color="red">Ninguem encontrado!</font>
<?php } } } else {?>
<font size="8" color="#006400">Selecionar a unidade e setor</font>
<?php } ?>

<?php 
	}//Fechar o if que verificar o usuario logado, e se ele tem permissao pra lista os usuarios
}	 
?> 

		</div>
		
		<br><br><div>
		<label>Desenvolvidor por:</label>
		<label>Gabriel do nascimento Borges</label>
		</div><br><br>
		
	</div>
</div>
		
</body>
</html>