<?php

/**
 * Description of Select
 *
 * @author Alexsandro Souza
 */
class Select extends DB {

	private $from;
	private $join;
	private $class;
	protected $filter;

	public function __construct($sqlSelect = ' * ', $class = null) {
		$this->class = $class;
		$this->select = $sqlSelect;
		$this->filter = new Filter();
	}

	public function from($from = null) {
		if (!$from) {
			if ($this->class) {
				$from = constant("{$this->class}::TB_NAME");
			}
		}
		$this->from = $from;
		return $this;
	}
	
	public function leftJoin($sTable){
		$this->join .= ' LEFT JOIN '. $sTable;
		return $this;
	}
	
	public function rightJoin($sTable){
		$this->join .= ' RIGHT JOIN '. $sTable;
		return $this;
	}
	
	public function innerJoin($sTable){
		$this->join .= ' INNER JOIN '. $sTable;
		return $this;
	}

	private function getFrom() {
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

	public function setFilter(Filter $filter) {
		$this->filter = $filter;
		return $this;
	}

	public function groupBy($group) {
		$this->filter->groupBy($group);
		return $this;
	}

	public function orderBy($order) {
		$this->filter->orderBy($order);
		return $this;
	}

	public function limit($limit, $offSet = 0) {
		$this->filter->limit($limit, $offSet);
		return $this;
	}

	public function fetchAll() {
		$stmt = $this->execute($this->getQuery());
		return $stmt->fetchAll();
	}

	public function fetchObject() {
		$stmt = $this->execute($this->getQuery());
		return $stmt->fetchObject($this->class);
	}

	public function fetchOne() {
		$stmt = $this->execute($this->getQuery());
		return $stmt->fetch();
	}

	public function fetchAllObject() {
		$stmt = $this->execute($this->getQuery());
		if ($stmt) {
			$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->class);
			return $stmt->fetchAll();
		} else {
			return false;
		}
	}

	public function getQuery() {
		$query = "SELECT {$this->select} ";
		$query .= " " . $this->getFrom();
		$query .= " " . $this->join;
		$query .= " " . $this->filter->getFilter();		
		return $query;
	}

}
