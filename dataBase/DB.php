<?php

/**
 * Description of DB
 *
 * @author Alexsandro Souza
 */
class DB {

	protected function connect() {
		$db_nome = DB_NOME;
		$db_senha = DB_SENHA;
		$db_usuario = DB_USUARIO;
		$db_host = DB_HOST;
		$db_porta = DB_PORTA;

		$db_porta = $db_porta ? $db_porta : '3306';

		$conn = new PDO("mysql:host={$db_host}; dbname={$db_nome}; port={$db_porta}", $db_usuario, $db_senha);

		// define o atributo error mode para lanÃ§ar exceÃ§Ãµes em caso de erro
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $conn;
	}

	public function execute($query, $isInsert = false) {
		$conn = $this->connect();
		try {
			$stmte = $conn->prepare($query);
			if(is_array($this->valueColumns)) {
				foreach ($this->valueColumns as $key => &$value) {
					$stmte->bindParam($key, $value);
				}
			}
			$stmte->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
			echo '<br>';
			echo $query;
			//header('location: tratarErro.php?pagina=' . getCurrentUrl() . '&msgErro=' . $e->getMessage());
			exit;
			//echo $query;
			//exit;
			return false;
		}

		if ($isInsert) {
			$this->id = $conn->lastInsertId();
		}

		$conn = null;
		return $stmte;
	}

	public function set($column, $value) {
		$this->valueColumns[$column] = $value;
		return $this;
	}

	protected function assignData($data) {
		foreach ($data as $column => $value) {
			$this->setRowData($column, $value);
		}
	}

	/**
	 * Armazena no vetor $valueColumns cada par coluna/valor do array ou objeto
	 * com a opÃ§Ã£o de aplicar htmlspecialchars em cada valor antes de armazenar no banco
	 * Usado apenas em INSERT e UPDATE
	 * @param array/Object $data 
	 * @param boolean $htmlEntities 
	 */
	public function data($data, $htmlEntities = true) {
		try {
			if (!is_array($data)) {
				if (is_object($data)) {
					$this->assignData(get_object_vars($data));
				} else {
					throw new Exception("Data deve ser um array associativo ou um objeto");
				}
			} else {
				$this->assignData($data);
			}
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}

		if ($htmlEntities) {
			foreach ($this->valueColumns as $chave => $value) {
				if (in_array($value, $data)) {
					$this->valueColumns[$chave] = htmlspecialchars($value, ENT_QUOTES);
				}
			}
		}

		return $this;
	}

	/**
	 * Armazena no vetor $valueColumns cada par coluna/valor
	 * Usado apenas em INSERT e UPDATE
	 * @param unknown_type $column 
	 * @param unknown_type $value
	 */
	public function setRowData($column, $value) {

		//as vezes vem y e x do form, nÃ£o sei por q, estou evitando eles
		if ($column == 'y' || $column == 'x') {
			return false;
		}

		// sï¿½ executa se for um dado escalar (string, inteiro, ...)
		if (is_scalar($value)) {
			if (is_string($value) && (!empty($value))) {
				$this->valueColumns[$column] = utf8_decode( "$value");
			} else if (is_bool($value)) {
				$this->valueColumns[$column] = utf8_decode($value ? 'TRUE' : 'FALSE');
			} else if ($value !== '') {
				$this->valueColumns[$column] = utf8_decode($value);
			} else {
				$this->valueColumns[$column] = 'NULL';
			}
		}
	}
	
	
		
	/**
	 * Método que recebe a clausula Where da query, com a opção de passar os valores num array associativo
	 * que será usado no método prepare do PDO
	 */
	public function where($sqlWhere, $bindParam= null)
	{		
		$this->filter->where($sqlWhere);
		
		if(is_array($bindParam)){
			foreach ($bindParam as $key => $value) {
				$this->valueColumns[$key] = $value;
			}
		}
		return $this;
	}

}
