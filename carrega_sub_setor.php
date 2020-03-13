<?php

include_once 'Conexao/Conexao.php';

$id = $_REQUEST['id_categoria'];

$conn = new Conexao();
$conn->openConnect();
mysqli_select_db($conn->getCon(), $conn->getBD());

$result_sub_cat = "SELECT ss.nome, ss.id FROM setor s INNER JOIN sub_setor ss ON
		(s.id = ss.id_setor) WHERE ss.id_setor = '".$id."'";
$resultado_sub_cat = mysqli_query($conn->getCon(), $result_sub_cat);

$conn->closeConnect ();

while ($row_sub_cat = mysqli_fetch_assoc($resultado_sub_cat) ) {
	$sub_categorias_post[] = array(
			'id'	=> $row_sub_cat['id'],
			'nome_sub_categoria' => utf8_encode($row_sub_cat['nome']),
	);
}

echo(json_encode($sub_categorias_post));

?>