<?php

/**
 * Description of Update
 *
 * @author Alexsandro Souza
 */

class Update extends DB
{

	private $table;
	private $where;
	protected $valueColumns = array();

	public function __construct($table = ' ', $class = null)
	{
		$this->class = $class;
		$this->table = $table;
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

	public function getQuery()
	{		
		foreach ($this->valueColumns as $column => $value) {
			$this->setRowData($column, $value);
		}

		if($this->valueColumns)
		{
			foreach ($this->valueColumns as $column=>$value)
			{
				$set[] = "{$column} = :{$column}";
			}
		}
		
		$sql = "UPDATE {$this->getTable()} ";
		$sql .= ' SET ' . implode(', ', $set);
		$sql .= $this->getWhere();
		echo $sql ;
		return $sql;
	}

	private function getTable()
	{
		try {
			if (!$this->table) {
				if ($this->class) {
					$this->table = constant("{$this->class}::TB_NAME");
				} else {
					throw new Exception("Informe uma tabela");
				}
			}
			return $this->table;
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}

	public function save()
	{
		$stmte = $this->execute($this->getQuery());
		return $stmte->rowCount();
	}

}

?>
