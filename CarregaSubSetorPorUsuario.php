<?php

include_once 'Conexao/Conexao.php';

$id = $_REQUEST['id_categoria'];

$conn = new Conexao();
$conn->openConnect();
mysqli_select_db($conn->getCon(), $conn->getBD());

$result_sub_cat = "SELECT ss.nome, ss.id as id_sub_setor, u.apelido, u.id as id_usuario 
				   FROM usuario u 
				   INNER JOIN setor_usuario su ON(u.id = su.id_usuario) 
				   INNER JOIN sub_setor ss ON(su.id_sub_setor = ss.id) 
				   WHERE u.id = '".$id."'";

$resultado_sub_cat = mysqli_query($conn->getCon(), $result_sub_cat);

$conn->closeConnect ();

while ($row_sub_cat = mysqli_fetch_assoc($resultado_sub_cat) ) {
	$sub_categorias_post[] = array(
			'id'	=> $row_sub_cat['id_sub_setor'],
			'nome_sub_categoria' => utf8_encode($row_sub_cat['nome']),
	);
}

echo(json_encode($sub_categorias_post));

?>