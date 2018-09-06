<?php
	/**
	*	Classe criada para gerar codigos SQL de select, insert, update e delete (Otimizar a vida é muito melhor :D)
	*	Author: Gabriel Azuaga Barbosa <gabrielbarbosaweb7@gmail.com>
	*	Github: https://github.com/gabrielweb7
	*	Site pessoal: http://gabrieldaluz.com.br
	*/
	class sqlTools {
	
		/**
		*	Variaveis
		*/
		private $method = false;
		protected $validMethods = array("select", "insert", "update", "delete");
		private $table = false;
		private $select = "*";
		private $where = false;
		private $order = false;
		private $limit = false;
		private $error = false;
		private $_postData = false;
		protected $_outSQL = false;
		
		/**
		*	Construct da Classe
		*/
		public function __construct($dataArray = false) { 
			
			/* Se data Array Existir */
			if($dataArray) { 

				/* Set Post Data */
				$this->_setPostData($dataArray);
			
			}
			
		}

		/* Gerar SQL para Select */
		protected function generateSqlSelect() {
			/* Criando Sql */
			$this->_outSQL = "select {$this->getSelect()} from {$this->getTable()} {$this->getWhere()} {$this->getOrder()} {$this->getLimit()}"; 
			$this->_outSQL .= ";";
		}
		
		/* Gerar SQL para Insert */
		protected function generateSqlInsert() {
			/* Criando Sql */
			$this->_outSQL = "insert into {$this->getTable()} ";
			/* Foreach */
			$foreachData = "";
			foreach($this->_getPostData() as $key => $value) {
				if(is_string($key)) { 
					$foreachData .= "{$key}, ";
				}
			}	
			$foreachData = substr($foreachData, 0, strlen($foreachData)-2);			
			$this->_outSQL .= "($foreachData) values ";
			/* Foreach */
			$foreachData = "";
			foreach($this->_getPostData() as $key => $value) {
				if(is_string($key)) { 
					$value = (gettype($value) == "string") ? "'{$value}'": $value;
					$value = (gettype($value) == "NULL") ? "NULL": $value;
					$foreachData .= "{$value}, ";
				}
			}	
			$foreachData = substr($foreachData, 0, strlen($foreachData)-2);			

			$this->_outSQL .= "($foreachData) ;";			
		}
		
		/* Gerar SQL para Update */
		protected function generateSqlUpdate() { 
		
			/* Criando Sql */
			$this->_outSQL = "update {$this->getTable()} set "; 
			/* Foreach */
			$foreachData = "";
			foreach($this->_getPostData() as $key => $value) {
				if(is_string($key)) { 
					$value = (gettype($value) == "string") ? "'{$value}'": $value;
					$value = (gettype($value) == "NULL") ? "NULL": $value;
					$foreachData .= "{$key} = {$value}, ";
				}
			}
			$foreachData = substr($foreachData, 0, strlen($foreachData)-2);
			$this->_outSQL .= " {$foreachData} {$this->getWhere()} ;";
		}
		
		/* Gerar SQL para Delete */
		protected function generateSqlDelete() { 
			/* Criando Sql */
			$this->_outSQL = "delete from {$this->getTable()} {$this->getWhere()} ;"; 
		}
		
		/**
		*	 Função criada para executar SQL gerado.
		*/
		public function prepareSql() { 
			
			/* Verifica está tudo correto antes de gerar SQL */
			$this->checkValidOptions();
			
			/* Caso não tenha nenhum erro.. gerar SQL */
			if(!$this->getError()) { 
			
				/* Gerando SQL */
				$this->generateSql();
				
				return $this->_outSQL;
				
			} else {
				
				/* Mostrando Error */
				die($this->getError());
				
			}
		}
		
			
		/**
		*	Função criada para gerar SQL DINAMICO
		*/
		public function generateSql() { 
			
			if($this->getMethod() == "select") { 
				/* Gerando Sql para Select */
				$this->generateSqlSelect();
			} 
			else if($this->getMethod() == "insert") {
				/* Gerando Sql para Select */
				$this->generateSqlInsert();
			}
			else if($this->getMethod() == "update") {
				/* Gerando Sql para Select */
				$this->generateSqlUpdate();
			}
			else if($this->getMethod() == "delete") {
				/* Gerando Sql para Select */
				$this->generateSqlDelete();
			}

		}
		
		/**
		*	Função criada para verificar se tudo está ok antes de gerar o SQL
		*/
		public function checkValidOptions() {
			
			
			/* Verificar se data é array */
			if(!is_array($this->_getPostData())) { 
				$this->setError("[sqlTools]::[__construct]: A variável recebida na coluna '\$dataArray' não é do tipo Array! ");
				return false; 
			}

			/* Verifica quantidade de posicoes */
			$_rad = array_keys($this->_getPostData());
			if(!is_string($_rad[0])) { 
				$this->setError("[sqlTools]::[__construct]: A variável recebida na coluna '\$dataArray' tem que ter todos os indices do tipo 'String' ! ");
				return false; 
			}

			/* Verifica se method existe */
			if(!$this->getMethod()) {
				$this->setError("[sqlTools]::[checkValidOptions]: A variável '\$method' não existe ! ");
				return false;
			}
			
			/* Verifica se method é valido */
			if(!in_array($this->getMethod(), $this->validMethods)) { 
				$this->setError("[sqlTools]::[checkValidOptions]: Somente os parametros 'select', 'insert', 'update', 'delete' é aceito na função ! ");
				return false;
			}
			
			/* Verifica se existe table */
			if(!$this->getTable()) { 
				$this->setError("[sqlTools]::[checkValidOptions]: A variável '\$table' não existe! ");
				return false;
			}
						
			/* Se caso method for update verificar se data foi recebida */
			if($this->getMethod() == "update" or $this->getMethod() == "insert") { 
				/* Se getPostData não existe.. retornar erro ! */
				if(!$this->_getPostData()) {
					$this->setError("[sqlTools]::[checkValidOptions]: A variável '\$__postData' não foi recebida corretamente! ");
					return false;
				}
			}
			
			
		}
		
		/**
		*	Getter's and Setter's
		*/
		public function setTable($data) {
			$this->table = $data;
		}
		public function getTable() {
			return $this->table;
		}
		
		public function setSelect($data) {
			$this->select = $data;
		}
		public function getSelect() {
			return $this->select;
		}	
		
		public function setMethod($data) {
			$this->method = $data;
		}
		public function getMethod() {
			return $this->method;
		}
		
		public function setWhere($data) {
			$this->where = $data;
		}
		public function getWhere() {
			$this->where = ($this->where) ? "where ".$this->where : false;
			return $this->where;
		}
		
		public function setOrder($data) {
			$this->order = $data;
		}
		public function getOrder() {
			$this->order = ($this->order) ? "order by ".$this->order : false;
			return $this->order;
		}
		
		public function setLimit($data) {
			$this->limit = $data;
		}
		public function getLimit() {
			$this->limit = ($this->limit) ? "limit ".$this->limit : false;
			return $this->limit;
		}
		
		public function setError($data) {
			$this->error = $data;
		}
		public function getError() {
			return $this->error;
		}
		
		public function _setPostData($data) {
			$this->_postData = $data;
		}
		public function _getPostData() {
			return $this->_postData;
		}
	
	}
	
	
?>