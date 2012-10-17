<?php


/**
 * Description of Select
 *
 * @author Alexsandro Souza
 */

class Select extends DB
{

	private $from;
	private $class;
	private $where;
	private $group;
	private $order;
	private $limit;

	public function __construct($sqlSelect = ' * ', $class = null)
	{
		$this->class = $class;
		$this->select = $sqlSelect;
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

	public function groupBy($group)
	{
		$this->group = " GROUP BY " . $group;
		return $this;
	}

	public function orderBy($order)
	{
		$this->order = " ORDER BY " . $order;
		return $this;
	}

	public function limit($limit, $offSet = 0)
	{
		$this->limit = "LIMIT " . $offSet . ' , ' . $limit;
		return $this;
	}

	public function fetchAll()
	{
		$stmt = $this->execute($this->getQuery());
		return $stmt->fetchAll();
	}

	public function fetchObject()
	{
		$stmt = $this->execute($this->getQuery());
		return $stmt->fetchObject($this->class);
	}

	public function fetchOne()
	{
		$stmt = $this->execute($this->getQuery());
		return $stmt->fetch();
	}

	public function fetchObjectAll()
	{
		$stmt = $this->execute($this->getQuery());
		$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->class);
		return $stmt->fetchAll();
	}

	public function getQuery()
	{
		$query = "SELECT {$this->select} ";
		$query .= " " . $this->getFrom();
		$query .= " " . $this->getWhere();
		$query .= " " . $this->group;
		$query .= " " . $this->order;
		$query .= " " . $this->limit;
		return $query;
	}

}
?>
