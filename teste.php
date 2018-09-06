<?php
	/**
	*	Classe criada para gerar codigos SQL de select, insert, update e delete (Otimizar a vida é muito melhor :D)
	*	Author: Gabriel Azuaga Barbosa <gabrielbarbosaweb7@gmail.com>
	*	Github: https://github.com/gabrielweb7
	*	Site pessoal: http://gabrieldaluz.com.br
	*/
	
	/* Iniciando Classe */
	require "sqlTools.class.php";
	
	/* Data para atualizar */
	$data["coluna1"] = "ae";
	$data["coluna2"] = "ae";
	$data["coluna3"] = 1234;
	$data["coluna4"] = NULL;
	$data["coluna5"] = "ae";
	
	/* Iniciando sqlTools SUPREMO :D */
	$sqlTools = new sqlTools($data);
	
	/* Variaveis obrigatorias */
	$sqlTools->setMethod("insert");
	$sqlTools->setTable("tabela");
	
	/* Variaveis opcionais */
	#$sqlTools->setSelect("id,coluna1,coluna2,coluna3");
	#$sqlTools->setWhere("id = 2");
	#$sqlTools->setOrder("id asc");
	#$sqlTools->setLimit(2);
	
	/* Gera o SQL */
	$resultadoSql = $sqlTools->prepareSql();
	echo $resultadoSql;
	
	/* Resultado: update tabela set coluna1 = 'ae', coluna2 = 'ae', coluna3 = 1234, coluna4 = NULL, coluna5 = 'ae' ;*/
	
?>