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
$mesa = $_POST['mesa'];
$tipoAtendente = $atendente[0];
$atendente = $atendente[1];

$q_ultimo = pg_exec($table,'SELECT MAX(cod_pedido) FROM "Pedido"');
$ultimo = pg_fetch_row($q_ultimo);

$q_item = pg_exec($table,'SELECT MAX(cod_item_pedido) FROM "ItemPedido"');
$item = pg_fetch_row($q_item);

$q_ultimaMesa = pg_exec($table,'SELECT MAX(cod_mesa_pedido) FROM "MesaPedido"');
$ultimaMesa = pg_fetch_row($q_ultimaMesa);

$ultimo = $ultimo[0];
$ultimo++;
$item = $item[0];
$item++;
$ultimaMesa = $ultimaMesa[0];
$ultimaMesa++;

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

	$horario = date("H:i:s");

	$q_mesa = 'INSERT INTO "MesaPedido"(cod_mesa_pedido,data,horario,cod_pedido,cod_mesa)
	 	       VALUES ('. $ultimaMesa . ",'" . $data . "','" . $horario . "'," . $ultimo . ',' . $mesa . ')
	';
	$resultMesa = pg_exec($table,$q_mesa);

	echo '<p>Sucesso</p>';
}

?>