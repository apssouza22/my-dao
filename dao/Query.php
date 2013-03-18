<?php

namespace dao;

/**
 * Description of DB_Query
 *
 * @author User
 */
class Query
{

	private $operation;

	public static function create($class = null)
	{
		$self = new self();
		$self->class = $class;
		return $self;
	}

	public function select($sqlSelect = '*')
	{
		$this->operation = new Select($sqlSelect, $this->class);
		return $this->operation;
	}

	public function insert($table = '')
	{
		$this->operation = new Insert($table, $this->class);
		return $this->operation;
	}

	public function update($table = '')
	{
		$this->operation = new Update($table, $this->class);
		return $this->operation;
	}

	public function delete($table = '')
	{
		$this->operation = new Delete($table, $this->class);
		return $this->operation;
	}

}

?>
