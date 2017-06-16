<?php

date_default_timezone_set('America/Sao_Paulo');

$table =  pg_connect(
			   'host=	localhost
				port=	5432
				dbname=	buteko
				user=	postgres
				password=123456'
);

$dados = $_POST['dados'];
$atendente = preg_split('[_]', $_POST['atendente']);
$tipoAtendente = $atendente[0];
$atendente = $atendente[1];

$q_ultimo = pg_exec($table,'SELECT MAX(cod_pedido) FROM "Pedido"');
$ultimo = pg_fetch_row($q_ultimo);

$q_item = pg_exec($table,'SELECT MAX(cod_item_pedido) FROM "ItemPedido"');
$item = pg_fetch_row($q_item);

$ultimo = $ultimo[0];
$ultimo++;
$item = $item[0];
$item++;

if($dados)
{

	$pedido = 'INSERT INTO "Pedido" (cod_pedido,status,data,';
	if($tipoAtendente=='ge') $pedido.='cod_gerente)';
	elseif($tipoAtendente=='ga') $pedido.='cod_garcom)';
	else $pedido.='cod_cliente)';

	$data = date("Y-m-d");

	$pedido.= 'VALUES (' . $ultimo . ",'Aguardando','" . $data . "'," . $atendente . ')';

	$result = pg_exec($table,$pedido);

	$query = 'INSERT INTO "ItemPedido" (cod_item_pedido,cod_chefe,cod_item_cardapio,cod_pedido) VALUES ';
	$linha = "";

	foreach ($dados as $row) {
		if(!empty($linha)) $linha.= ',';
		$linha.= '(' . $item . ',1,' . $row . ',' . $ultimo . ')';
		$item++; 
	}
	$query.=$linha;
	$result = pg_exec($table,$query);

	echo '<p>Sucesso</p>';
}

?>