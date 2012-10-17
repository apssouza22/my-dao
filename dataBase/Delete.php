<?php

/**
 * Description of Delete
 *
 * @author Alexsandro Souza
 */
class Delete extends DB
{
	private $from;
	private $class;
	private $where;
	private $limit;

	public function __construct($table = null, $class = null)
	{
		$this->class = $class;
		$this->from = $table;
	}

	public function from($from = null)
	{
		if (!$from) {
			if ($this->class) {
				$from = constant("{$this->class}::TB_NAME");
			}
		}
		$this->from = $from;
		return $this;
	}

	private function getFrom()
	{
		try {
			if (!$this->from) {
				if ($this->class) {
					$this->from = constant("{$this->class}::TB_NAME");
				} else {
					throw new Exception("Informe uma tabela");
				}
			}
			return " FROM " . $this->from;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	private function getWhere()
	{
		return $this->where ? " WHERE " . $this->where : " WHERE 1 ";
	}

	public function where($sqlWhere, $bindParam= null)
	{		
		$this->where = $sqlWhere;
		if(is_array($bindParam)){
			foreach ($bindParam as $key => $value) {
				$this->valueColumns[$key] = $value;
			}
		}
		return $this;
	}

	public function limit($limit)
	{
		$this->limit = "LIMIT "  . $limit;
		return $this;
	}

	

	public function getQuery()
	{
		$query = "DELETE ";
		$query .= " " . $this->getFrom();
		$query .= " " . $this->getWhere();
		$query .= " " . $this->limit;
		return $query;
	}
	
	public function exec()
	{
		$stmte = $this->execute($this->getQuery());
		return $stmte->rowCount();
	}

}

?>
