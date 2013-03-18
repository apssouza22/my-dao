<?php

namespace dao;

/**
 * Description of DB
 *
 * @author Alexsandro Souza
 */
class DB
{

	public static $utf8Convert = false;
	public static $debug = false;
	protected $valueColumns;
	protected $conn;

	public function execute($query, $isInsert = false)
	{
		try {
			$stmte = $this->conn->prepare($query);
			if (is_array($this->valueColumns)) {
				foreach ($this->valueColumns as $key => &$value) {
					$stmte->bindParam($key, $value);
				}
			}
			$stmte->execute();
		} catch (\PDOException $e) {
			if (self::$debug) {
				echo $e->getMessage();
				echo '<br>';
				echo $query;
			}
			throw new \Exception($e->getMessage());
			return false;
		}

		if ($isInsert) {
			$this->id = $this->conn->lastInsertId();
		}

		$this->conn = null;
		return $stmte;
	}

	public function set($column, $value)
	{
		$this->valueColumns[$column] = $value;
		return $this;
	}

	protected function assignData($data)
	{
		foreach ($data as $column => $value) {
			$this->setRowData($column, $value);
		}
	}

	/**
	 * Armazena no vetor $valueColumns cada par coluna/valor do array ou objeto
	 * com a opção de aplicar htmlspecialchars em cada valor antes de armazenar no banco
	 * Usado apenas em INSERT e UPDATE
	 * @param array/Object $data 
	 * @param boolean $htmlEntities 
	 */
	public function data($data, $htmlEntities = true)
	{
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
	public function setRowData($column, $value)
	{

		//as vezes vem y e x do form, não sei por q, estou evitando eles
		if ($column == 'y' || $column == 'x') {
			return false;
		}

		// s� executa se for um dado escalar (string, inteiro, ...)
		if (is_scalar($value)) {
			if (is_string($value) && (!empty($value))) {
				$this->valueColumns[$column] = self::$utf8Convert ? utf8_decode($value) : $value;
			} else if (is_bool($value)) {
				$this->valueColumns[$column] = $value ? 'TRUE' : 'FALSE';
			} else if ($value !== '') {
				$this->valueColumns[$column] = self::$utf8Convert ? utf8_decode($value) : $value;
			} else {
				$this->valueColumns[$column] = 'NULL';
			}
		}
	}

	/**
	 * M�todo que recebe a clausula Where da query, com a op��o de passar os valores num array associativo
	 * que ser� usado no m�todo prepare do PDO
	 */
	public function where($sqlWhere, $bindParam = null)
	{
		$this->filter->where($sqlWhere);

		if (is_array($bindParam)) {
			foreach ($bindParam as $key => $value) {
				$this->valueColumns[$key] = $value;
			}
		}
		return $this;
	}

}
